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

class DraftOrdersDeleteJob implements ShouldQueue
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

        // //check if order already exist in database using order_id and shop_id and delete where status is 1
        $order = Order::where('order_id', $data->id)->where('shop_id', $shop_id)->where('status', 1)->first();
        if($order){
            //delete order
            $order->delete();
        }

    }
}
