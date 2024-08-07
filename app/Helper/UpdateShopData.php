<?php

namespace App\Helper;

use App\Models\Shop;
use App\Models\User;

use Illuminate\Support\Facades\Log;

use App\Jobs\ProductCreateJob;
use App\Jobs\CreateWebhookJob;

class UpdateShopData
{
    public static function updateShopData($shop)
    {

        // dispatch product create (protection Product) job if no product exists
        ProductCreateJob::dispatch($shop);
        // CreateWebhookJob::dispatch($shop);

        try {
            // get shop data using rest api
            $data = $shop->api()->rest('GET', '/admin/shop.json');
            $shop_data = $data['body']['shop'];

            //update user data
            $shop->shop_id = $shop_data['id'];
            $shop->shop_gid = "gid://shopify/Shop/" . $shop_data['id'];
            $shop->update();

            // Create or update shop data in the database
            Shop::updateOrCreate(
                ['user_id' => $shop->id],
                [
                    'user_id' => $shop->id,
                    'shop_id' => $shop_data['id'],
                    'domain' => $shop_data['myshopify_domain'],
                    'data' => json_encode($shop_data),
                    'status' => true,
                    'primary_location_id' => $shop_data['primary_location_id'],
                    'admin_graphql_api_id' => "gid://shopify/Shop/" . $shop_data['id'],
                    'app_url' => env('APP_URL', 'http://hello'),

                ]
            );


            $appUninstallWebhook =  $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', [
                'webhook' => [
                    'topic' => 'app/uninstalled',
                    'address' => route('webhook.app.uninstalled'),
                    'format' => 'json'
                ]
            ]);
        
            $ordersPaidJob =  $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', [
                'webhook' => [
                    'topic' => 'orders/paid',
                    'address' => route('webhook.orders.paid'),
                    'format' => 'json'
                ]
            ]);
        
            //log job
            log::info('webhook job --------', ['app uninstall' => $appUninstallWebhook, 'order paid job' => $ordersPaidJob]);

        } catch (\Exception $e) {
            // log error
            Log::error('UpdateShopData-------------error', ['error' => $e->getMessage()]);
        }
    }
}
