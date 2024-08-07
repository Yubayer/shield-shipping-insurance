<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Widget;

use App\Jobs\WidgetStyleMetafieldJob;

class WidgetsController extends Controller
{
    public function index(Request $request)
    {
        // // log the request
        // Log::info('WidgetsController ---------- ', $request->all());

        $authShop = User::where('name', $request->domain)->first();
        if (!$authShop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop not found'
            ]);
        }

        $key = $request->key;
        
        if($authShop->widget->styles){
            $styles = $authShop->widget->styles;
            $style = $styles[$key];
        } else {
            $style = null;
        }

        foreach($style as $k => $value){
            $style[$k] = $request[$k];
        }

        $styles[$key] = $style;

        Widget::where('user_id', $authShop->id)->update(['styles' => $styles]);

        $this->syncWidgetWithShop($request->domain);

        return response()->json([
            'status' => 'success',
            'data' => $styles,
            'key' => $key
        ]);
    }

    public function syncWidgetWithShopMetafields(Request $request)
    {
        $authShop = User::where('name', $request->domain)->first();
        if (!$authShop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop not found'
            ], 404);
        }

        // WidgetStyleMetafieldJob::dispatch($authShop);
        $this->syncWidgetWithShop($authShop);

        return response()->json([
            'status' => 'success',
            'message' => 'Widget style syncd with shop successfully'
        ], 200);

    }

    public function syncWidgetWithShop($domain) {
        $shop = User::where('name', $domain)->first();
        $shop_id = $shop->shop->shop_id;
        $widgets = $shop->widget;
        $styles = $widgets->styles;

        if(!$styles){
            $styles = [];
        }

        $json_style = json_encode($styles);

        //create shop metafiled for widget style
        $metafieldsData = [
            "shop_id" => $shop_id,
            "namespace" => "protection",
            "key" => "styles",
            "value" => $json_style,
            "type" => "json",
            "ownerId" => $shop->shop->admin_graphql_api_id,
        ];

        //create shop metafield 
        $responseData = $shop->api()->rest('POST', '/admin/api/2024-04/metafields.json', [
            'metafield' => $metafieldsData
        ]);


        //log json style
        Log::info('WidgetStyleMetafieldJob - api controller / updated style ------------', ['json style' => $responseData]);
    }
}
