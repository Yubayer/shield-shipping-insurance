<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//use user model
use App\Models\User;

// use log
use Illuminate\Support\Facades\Log;


class CreateWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $shop;

    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $shop = $this->shop;
        
            if(!$shop){
                Log::info('CreateWebhookJob - shop not found ------------', ['shop' => $shop]);
                return;
            }
        
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
            Log::error('Error creating webhooks', ['error' => $e->getMessage()]);
        }
    }
}
