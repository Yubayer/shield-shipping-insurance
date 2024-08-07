<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//App\Helper;
use App\Helper\OrderCreateOrUpdate;

use Illuminate\Support\Facades\Log; // Add this line to import the Log class

class OrdersPaidJob implements ShouldQueue
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

        //log
        Log::info('OrdersPaidJob Triggered-------------', ['domain' => $domain, 'data' => $data]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //create order on shopify store name Order Protection using rest api
        $domain = $this->domain;
        $data = $this->data;

        // log order pain job
        Log::info('OrdersPaidJob------------- shop order data', ['order data from shopify' => $data]);


        OrderCreateOrUpdate::createOrUpdateOrder($domain, $data, 1);

    }
}
