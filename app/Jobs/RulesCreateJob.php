<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//model user, shop
use App\Models\User;

//log
use Illuminate\Support\Facades\Log;

class RulesCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

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

            //metafiledMutation for rules
            // $metafieldMutation = 'mutation MetafieldsSet($metafields: [MetafieldsSetInput!]!) {
            //     metafieldsSet(metafields: $metafields) {
            //     metafields {
            //         id
            //         key
            //         namespace
            //         value
            //         createdAt
            //         updatedAt
            //         type
            //     }
            //         field
            //          message
            //          code
            //     }
            //   }
            // }';

            // $metafieldsData = [
            //     "namespace" => "protection",
            //     "key" => "rules",
            //     "value" => $rules_data,
            //     "type" => "json",
            //     "ownerId" => "gid://shopify/Shop/{$shop['id']}",
            // ];

            // $responseData = $authShop->api()->graph($metafieldMutation, [
            //     'metafields' => $metafieldsData,
            // ]);

            $metafieldsData = [
                "shop_id" => $shop['id'],
                "namespace" => "protection",
                "key" => "rules",
                "value" => $rules_data,
                "type" => "json",
                "ownerId" => "gid://shopify/Shop/{$shop['id']}",
            ];

            //create shop metafield 
            $responseData = $authShop->api()->rest('POST', '/admin/api/2024-04/metafields.json', [
                'metafield' => $metafieldsData
            ]);


            //log rules data
            Log::info('RulesCreateJob-------------success', ['rules metafiled data' => $responseData]);
        } catch (\Exception $e) {
            Log::error('RulesCreateJob-------------error', ['error' => $e->getMessage()]);
        }
    }
}
