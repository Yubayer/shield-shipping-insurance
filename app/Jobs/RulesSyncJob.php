<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//model user
use App\Models\User;

//log
use Illuminate\Support\Facades\Log;

class RulesSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domain;



    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $authShop = User::where('name', $this->domain)->first();

            $shop = json_decode($authShop->shop->data, true);
            $settings = $authShop->settings;

            if ($settings) {
                $rules_data = $settings->rules;
            } else {
                $rules_data = json_encode([]);
            }

            $metafieldsData = [
                "shop_id" => $shop['id'],
                "namespace" => "protection",
                "key" => "rules",
                "value" => $rules_data,
                "type" => "json",
                "ownerId" => $authShop->shop->admin_graphql_api_id,
            ];

            //create shop metafield 
            $responseData = $authShop->api()->rest('POST', '/admin/api/2024-04/metafields.json', [
                'metafield' => $metafieldsData
            ]);


            //log rules data
            Log::info('RulesSyncJob-------------success', ['rules metafiled data' => $responseData]);
        } catch (\Exception $e) {
            Log::error('RulesSyncJob-------------error', ['error' => $e->getMessage()]);
        }
    }
}
