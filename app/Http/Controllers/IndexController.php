<?php

namespace App\Http\Controllers;

use App\Helper\AppUrlMetafiledSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

//model user, shop
use App\Models\User;

//job
use App\Jobs\ProductCreateJob;
use App\Jobs\CreateWebhookJob;


//use Graphql
use Shopify\Clients\Graphql;


// use Auth
use Illuminate\Support\Facades\Auth;

//use UpdateShopData
use App\Helper\UpdateShopData;
use App\Helper\AppSettingsMetafieldSync;



class IndexController extends Controller
{
    public function index(Request $request)
    {
        $shop = Auth::user();

        if ($shop) {

            // AppUrlMetafiledSync::appUrlSync($shop->name);

            //webhook create job
            // CreateWebhookJob::dispatch($shop->name);

            // $this->showWebhook($shop);
            // $this->getAllVariants($shop);

            // AppUrlMetafiledSync::appUrlSync($shop->name);
        }
        // call dashboard method from dashboard controller
        $dashboard = new DashboardController();
        return $dashboard->dashboard($request);
        
        // return view('welcome', compact('shop'));
    }
    

    //get All variant
    public function getAllVariants($shop)
    {
        
        $product_id = $shop->products->first()->product_id;
        //get all variant of product_id
        $variants_response = $shop->api()->rest('GET', '/admin/api/2024-04/products/' . $product_id . '/variants.json');
        $variants = $variants_response['body']['variants'];

        // print all variants which cretaed at 15hrs ago
        $variants_created_at_15hrs_ago = [];

        foreach ($variants as $key => $variant) {
            $created_at = $variant['created_at'];
            $created_at_timestamp = strtotime($created_at);
            $twenty_minutes_ago = strtotime('-10 minutes');

            if ($created_at_timestamp <= $twenty_minutes_ago) {
            if ($variant['position'] != 1) {
                $variants_created_at_15hrs_ago[] = $variant;
            }
            }
        }

        dump('Variants created 15 hours ago:', $variants_created_at_15hrs_ago);

    }

    //show all existing webhook 
    public function showWebhook($shop)
    {

        // if ($shop->products->count() == 0) ProductCreateJob::dispatch($shop);
        // UpdateShopData::updateShopData($shop->name);

        // $orderPaindWebhookCreate = $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', [
        //     'webhook' => [
        //         'topic' => 'carts/update',
        //         'address' => route('webhook.carts.update'),
        //         'format' => 'json'
        //     ]
        // ]);
        // $orderPaindWebhookCreate = $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', [
        //     'webhook' => [
        //         'topic' => 'orders/create',
        //         'address' => route('webhook.orders.create'),
        //         'format' => 'json'
        //     ]
        // ]);

        // $orderPaindWebhookCreate = $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', [
        //     'webhook' => [
        //         'topic' => 'app/uninstalled',
        //         'address' => env('SHOPIFY_WEBHOOK_ORDERS_PAID'),
        //         'format' => 'json'
        //     ]
        // ]);

        // dump("order paid webhook created");
        // dump($orderPaindWebhookCreate);

        $webhooks = $shop->api()->rest('GET', '/admin/api/2024-04/webhooks.json');
        $webhooks_data = $webhooks['body']['webhooks'];

        
            // $webhook_id_1 = '1307826487511';
            // //update webhook
            // $update_webhook = $shop->api()->rest('PUT', '/admin/api/2024-04/webhooks/'.$webhook_id_1.'.json', [
            //     'webhook' => [
            //         'id' => $webhook_id_1,
            //         'address' => route('webhook.app.uninstalled'),
            //         'format' => 'json'
            //     ]
            // ]);

            // $webhook_id_cu = '1550778695954';
            //update webhook
            // $update_webhook = $shop->api()->rest('PUT', '/admin/api/2024-04/webhooks/'.$webhook_id_cu.'.json', [
            //     'webhook' => [
            //         'id' => $webhook_id_cu,
            //         'address' => route('webhook.carts.update'),
            //         'format' => 'json'
            //     ]
            // ]);

            // $webhook_id_2 = '1307826520279';
            // //update webhook
            // $update_webhook2 = $shop->api()->rest('PUT', '/admin/api/2024-04/webhooks/'.$webhook_id_2.'.json', [
            //     'webhook' => [
            //         'id' => $webhook_id_2,
            //         'address' => route('webhook.app.uninstalled'),
            //         'format' => 'json'
            //     ]
            // ]);

            // dump("Webhook Updated");
            // dump($update_webhook);
            // dump($update_webhook2);
        

        //Hello
        //log data
        Log::info('Webhook Data--------------------', ['data' => $webhooks_data]);

        $locations = $shop->api()->rest('GET', '/admin/api/2024-04/locations.json');
        $locations_data = $locations['body']['locations'];

        dump("Locations Data");
        dump($locations_data);

        // dump data
        dump("Webhooks Data");
        dump($webhooks_data);
    }

    public function testAppBridge(Request $request)
    {
        //return test.blade
        return view('test');
    }
}
