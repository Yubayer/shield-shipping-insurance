<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Settings;
use App\Models\Shop;

use App\Jobs\RulesSyncJob;
use App\Jobs\ExistingVariantDeleteJob;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Helper;

//use Helper
use App\Helper\MakeRedirect as ShopifyRedirect;


class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->missing('shop')) {
            return redirect()->route('home');
        }

        $authShop = User::where('name', $request->shop)->first();
        $settings = $authShop->settings;

        if ($settings == null) {
            $settings = new Settings();
            $settings->shop_id = $authShop->shop->shop_id;
            $settings->user_id = $authShop->id;
            $settings->rules = json_encode([]);
            $settings->save();
        }

        $viewData = $this->generateViewData($request->shop);
        // dd(view('settings.index', $viewData));
        // return Redirect::tokenRedirect('settings.index', ['settings' => $settings]);
        return view('settings.index', $viewData);
    }

    public function generateViewData($domain)
    {
        $authShop = User::where('name', $domain)->first();
        $shop = $authShop->shop;
        $settings = $authShop->settings;
        $rules = json_decode($settings->rules, true);
        $domain = $authShop->name;

        return compact('shop', 'rules', 'settings', 'domain');
    }

    public function ruleCreate(Request $request)
    {
        if ($request->missing('shop')) {
            //log error
            Log::error('RuleCreate-------------error', ['error' => 'Shop domain missing']);
            return redirect()->route('home');
        }

        //createOrupdate Settings rules in database setting table
        try {
            $authShop = User::where('name', $request->domain)->first();
            $settings = $authShop->settings;
            $rules = [];

            //if settings rules already exists then update it otherwise create new marge with previous rules from settings
            if ($request->has('category') && $request->category == 'create') {
                $rules = json_decode($settings->rules, true);
                $rules[] = [
                    'from' => $request->from,
                    'to' => $request->to,
                    'type' => $request->type,
                    'value' => $request->value,
                ];
            } elseif ($request->has('category') && $request->category == 'update') {
                if($request->from) {
                    $length = count($request->from);
                    for ($i = 0; $i < $length; $i++) {
                        $rules[] = [
                            'from' => $request->from[$i],
                            'to' => $request->to[$i],
                            'type' => $request->type[$i],
                            'value' => $request->value[$i],
                        ];
                    }
                } else {
                    $rules = [];
                }

                // $length = count($request->from);
                // for ($i = 0; $i < $length; $i++) {
                //     $rules[] = [
                //         'from' => $request->from[$i],
                //         'to' => $request->to[$i],
                //         'type' => $request->type[$i],
                //         'value' => $request->value[$i],
                //     ];
                // }
            }

            $settings = Settings::updateOrCreate(
                ['shop_id' => $authShop->shop->shop_id],
                [
                    'user_id' => $authShop->id,
                    'shop_id' => $authShop->shop->shop_id,
                    'rules' => json_encode($rules),
                ]
            );

            //dispatch RulesSybcJob [sync with metafield]
            RulesSyncJob::dispatch($authShop->name);

            $viewData = $this->generateViewData($request->domain);
            return view('settings.index', $viewData)
                ->with('status', 'success')
                ->with('message', 'Rules ' . $request->key . ' successfully')
                ->with('type', $request->key);
        } catch (\Exception $e) {
            $viewData = $this->generateViewData($request->domain);
            return view('settings.index', $viewData)
                ->with('status', 'error')
                ->with('message', 'Server Error Occurred');
        }
    }

    public function getRedirectRoute($routeName, $params = []) {
        // dump($params);
        $domain = $params['domain'];
        $shop = User::where('name', $domain)->first();
        $shopDomain = str_replace(".myshopify.com", "", $shop->getDomain()->toNative());
        $path = URL::tokenRoute($routeName, $params);
        //replace http with https
        $path = str_replace("http", "https", $path);
        $path .= "&host=" . base64_encode("admin.shopify.com/store/" . $shopDomain);
        return $path;
    }

    
    //syncRuleWithShopMetafields
    public function syncRuleWithShopMetafields(Request $request)
    {
        //dispatch RulesSybcJob [sync with metafield]
        RulesSyncJob::dispatch($request->domain);

        try {
            return response()->json(['message' => 'Rules synced with shop metafields successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to sync rules with shop metafields'], 500);
        }
    }

    //protectionVariantCheck
    public function ProtectionVariantCheck(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $protection_cost = $request->protection_cost;

            $authShop = User::where('name', $request->shop)->first();
            $shop = $authShop->shop;

            // create variant of product_id using admin rest api shopify
            $variant = $authShop->api()->rest('POST', '/admin/api/2024-04/products/' . $product_id . '/variants.json', [
                'variant' => [
                    'option1' => 'Protection-' . date('Y-m-d H:i:s'),
                    'price' => $protection_cost,
                    'barcode' => 'protection',
                ]
            ]);

            $variant_id = $variant['body']['variant']['id'];
            $inventory_item_id = $variant['body']['variant']['inventory_item_id'];

            //update variant inventory_quantity using inventory_levels api
            $updatedVariantQuantity = $authShop->api()->rest('POST', '/admin/api/2024-04/inventory_levels/set.json', [
                'location_id' => $shop->primary_location_id,
                'inventory_item_id' => $inventory_item_id,
                'available' => 2,
            ]);

            //log updated variant
            Log::info('ProtectionVariantCheck-------------updated variant data', ['updated variant data' => $updatedVariantQuantity]);

            return response()->json(['variant_id' => $variant_id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Variant creation error'], 500);
        }
    }

    //protectionVariantDelete
    public function ProtectionVariantDelete(Request $request)
    {
        try {
            $variant_id = $request->variant_id;
            $domain = $request->shop;

            //dispatch job ExistsVariantDeleteJob
            ExistingVariantDeleteJob::dispatch($domain, $variant_id);

            return response()->json(['message' => 'Variant deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Variant deletion error'], 500);
        }
    }

    //configCheckoutUi
    public function configCheckoutUi(Request $request)
    {
        // log request data
        Log::info('configCheckoutUi-------------request data', ['request data' => $request->all()]);
        try {
            $authShop = User::where('name', $request->shop)->first();
            $shop = $authShop->shop;

            $product_id = $authShop->products->first()->product_id;
            $rules = json_decode($authShop->settings->rules, true);

            $config = [
                'shop' => [
                    'domain' => $shop->domain,
                    'currency' => $shop->currency,
                    'primary_location_id' => $shop->primary_location_id,
                ],
                'product' => [
                    'id' => $product_id,
                ],
                'rules' => $rules,
            ];
            return response()->json(['config' => $config], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get checkout ui config'], 500);
        }
    }

    //AppActivate
    public function AppActivate(Request $request)
    {
        try {
            $shop = Shop::where('domain', $request->domain)->first();
            $shop->status = !$shop->status;
            $shop->save();

            $settings = Settings::where('shop_id', $shop->shop_id)->first();
            $settings->status = !$shop->status;
            $settings->save();

            if ($shop->status == true) {
                $updatedStatus = '1';
                $message = 'App activated successfully';
            } else {
                $updatedStatus = '0';
                $message = 'App deactivated successfully';
            }

            $viewData = $this->generateViewData($request->domain);
            return view('settings.index', $viewData)
                ->with('status', 'success')
                ->with('message', 'App Updated Successfully')
                ->with('type', 'Status');
        } catch (\Exception $e) {
            $viewData = $this->generateViewData($request->domain);
            return view('settings.index', $viewData)
                ->with('status', 'success')
                ->with('message', 'App Updated Failed')
                ->with('type', 'Status');
        }
    }
}
