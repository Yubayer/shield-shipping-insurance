<?php
namespace App\Helper;

use Illuminate\Support\Facades\Log;

class AppActivationMetafieldSync
{
    public static function appActivationSync($shop, $isActivated=false)
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
                "key" => "isActivated",
                "value" => $isActivated? "true": "false",
                "type" => "single_line_text_field",
                "ownerId" => $shop->shop->admin_graphql_api_id,
            ];

            $responseDataAppUrl = $shop->api()->graph($metafieldMutationAppURL, [
                'metafields' => $metafieldsDataAppUrl,
            ]);

            //log metafield data
            Log::info('App IsActivated Metafield Synced------------App isActivated metafield data', ['metafield response' => $responseDataAppUrl]);
        } catch (\Exception $e) {
            // log error
            Log::error('AppIsActivatedMetafiledSync-------------error', ['error' => $e->getMessage()]);
        }
    }
}