<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

use App\Models\Metafield;

class ProtectionProductMetafiledSync
{
    public static function protectionProductSync($shop, $product_data)
    {
        try {
            $shop_id = $shop->shop->shop_id;

            //metafiledMutation for protection product
            $metafieldMutation = 'mutation MetafieldsSet($metafields: [MetafieldsSetInput!]!) {
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

            $metafieldsData = [
                "namespace" => "protection",
                "key" => "product",
                "value" => $product_data,
                "type" => "json",
                "ownerId" => $shop->shop->admin_graphql_api_id,
            ];

            $responseData = $shop->api()->graph($metafieldMutation, [
                'metafields' => $metafieldsData,
            ]);

            $createdMetafieldData = $responseData['body']['data']['metafieldsSet']['metafields'][0];
            
            //update metafield in database
            Metafield::updateOrCreate(
                ['shop_id' => $shop_id],
                [
                    'user_id' => $shop->id,
                    'shop_id' => $shop_id,
                    'product_metafield' => json_encode($createdMetafieldData),
                ]
            );

            //log metafield data
            Log::info('Protection Product Metafield Synced------------metafield data', ['metafield response' => $responseData]);
        } catch (\Exception $e) {
            // log error
            Log::error('Protection Product Metafield Synced-------------error', ['error' => $e->getMessage()]);
        }
    }
}
