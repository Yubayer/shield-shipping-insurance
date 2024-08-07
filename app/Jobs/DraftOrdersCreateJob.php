<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


//App\Helper;
use App\Helper\OrderCreateOrUpdate;

class DraftOrdersCreateJob implements ShouldQueue
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

        // OrderCreateOrUpdate::createOrUpdateOrder($domain, $data, 0);
    }
}
