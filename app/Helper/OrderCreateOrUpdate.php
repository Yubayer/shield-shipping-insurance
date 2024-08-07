<?php

namespace App\Helper;

//model
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

//App\Helper
use App\Helper\DeleteExistsVariant;

// log
use Illuminate\Support\Facades\Log;

class OrderCreateOrUpdate
{
    public static function createOrUpdateOrder($domain, $data, $order_status = 1)
    {
        //create order on shopify store name Order Protection using rest api
        $domain = $domain;
        $data = $data;

        //get shop data
        $shop = User::where('name', $domain)->first();

        $shop_id = $shop->shop->shop_id;
        $user_id = $shop->id;
        $protection_status = 0;
        $protection_price = 0;

        $products = $shop->products;


        // check order line items with $products
        foreach ($products as $key => $product) {
            foreach ($data['line_items'] as $key => $line_item) {
                if ($product->product_id == $line_item['product_id']) {
                    //delete exists variant after order create or update
                    DeleteExistsVariant::deleteExistsVariant($shop, $line_item['variant_id']);
        
                    $protection_price = $line_item['price'];
                    $protection_status = 1;
                    break;
                }
            }
            if ($protection_status == 1) {
                break;
            }
        }

        //order Create or update using UpdateOrCreate method
        $newOrder = Order::updateOrCreate(
            ['order_id' => $data['id'], 'shop_id' => $shop_id],
            [
                'shop_id' => $shop_id,
                'user_id' => $user_id,
                'order_id' => $data['id'],
                'name' => $data['name'],
                'order_number' => $data['name'],
                'data' => json_encode($data),
                'line_items' => json_encode($data['line_items']),
                'order_status' => $order_status,
                'protection_status' => $protection_status,
                'total_price' => $data['total_price'],
                'protection_price' => $protection_price,
                'subtotal_price' => $data['subtotal_price'],
                'total_tax' => $data['total_tax'],
                'admin_graphql_api_id' => $data['admin_graphql_api_id']
            ]
        );

        //log new order
        Log::info('OrderCreateOrUpdate-------------createdNewOrder', ['createdNewOrder' => $newOrder]);
    }
}
