<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//import model
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;

use Illuminate\Support\Facades\Log; // Add this line to import the Log class

class OrdersFulfilledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $domain;
    protected $data;

    public function __construct($domain, $data)
    {
        $this->domain = $domain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //create order on shopify store name Order Protection using rest api
        $domain = $this->domain;
        $data = $this->data;

        //get shop data
        $shop = User::where('name', $domain)->first();

        $shop_id = $shop->shop->shop_id;
        $user_id = $shop->id;

        // //check if order already exist in database using order_id and shop_id and update status to 2
        $order = Order::where('order_id', $data->id)->where('shop_id', $shop_id)->first();
        if($order){
            //update order status to 2
            $order->status = 3;
            $order->save();
        } else {
            $order = new Order();
            $order->shop_id = $shop_id;
            $order->user_id = $user_id;
            $order->order_id = $data->id;
            $order->name = $data->name;
            $order->order_number = $data->order_number;
            $order->data = $data;
            $order->line_items = $data->line_items;
            $order->order_status = 3;
            $order->total_price = $data->total_price;
            $order->subtotal_price = $data->subtotal_price;
            $order->total_tax = $data->total_tax;
            $order->total_discounts = $data->total_discounts;
            $order->admin_graphql_api_id = $data->admin_graphql_api_id;
            $order->save();
        }
    }
}
