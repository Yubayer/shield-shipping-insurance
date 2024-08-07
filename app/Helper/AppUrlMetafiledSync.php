<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

use App\Models\User;

class AppUrlMetafiledSync
{
    public static function appUrlSync($shop)
    {

        // dump(env('APP_URL'));
        // dump(env('APP_URL', 'https://novaalab.articmaze.dev'));

        try {
            //metafiledMutation for protection product
            $metafieldMutationAppURL = 'mutation MetafieldsSet($metafields: [MetafieldsSetInput!]!) {
                metafieldsSet(metafields: $metafields) {
                    metafields {
                        id
                        key
                        namespace
                        value
                        createdAt
                        updatedAt
                        type
                    }
                    userErrors {
                        field
                        message
                        code
                    }
                }   
            }';

            $metafieldsDataAppUrl = [
                "namespace" => "protection",
                "key" => "app_url",
                "value" => env('APP_URL', 'https://novaalab.articmaze.dev'),
                "type" => "single_line_text_field",
                "ownerId" => $shop->shop->admin_graphql_api_id,
            ];

            $responseDataAppUrl = $shop->api()->graph($metafieldMutationAppURL, [
                'metafields' => $metafieldsDataAppUrl,
            ]);


            //log metafield data
            Log::info('App URL Metafield Synced------------App url metafield data', ['metafield response' => $responseDataAppUrl]);
        } catch (\Exception $e) {
            // log error
            Log::error('AppUrlMetafiledSync-------------error', ['error' => $e->getMessage()]);
        }
    }
}
