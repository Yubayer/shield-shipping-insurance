<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

class AppSettingsMetafieldSync
{
    public static function appSettingsSync($shop, $isModal=false)
    {
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
                "key" => "isModal",
                "value" => $isModal? "true": "false",
                "type" => "single_line_text_field",
                "ownerId" => $shop->shop->admin_graphql_api_id,
            ];

            $responseDataAppUrl = $shop->api()->graph($metafieldMutationAppURL, [
                'metafields' => $metafieldsDataAppUrl,
            ]);


            //log metafield data
            Log::info('App IsModal Metafield Synced------------App isModal metafield data', ['metafield response' => $responseDataAppUrl]);
        } catch (\Exception $e) {
            // log error
            Log::error('AppIsModalMetafiledSync-------------error', ['error' => $e->getMessage()]);
        }
    }
}
