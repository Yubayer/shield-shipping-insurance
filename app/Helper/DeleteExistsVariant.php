<?php
//namespace
namespace App\Helper;

// user Exception class
use Exception;

//use Log class
use Illuminate\Support\Facades\Log;



class DeleteExistsVariant
{
    public static function deleteExistsVariant($shop, $variant_id)
    {
        if ($shop) {
            $variant_ids = self::getAllVariants($shop, $variant_id);
            $product_id = "gid://shopify/Product/" . $shop->products->first()->product_id;

            try {
                // productVariantsBulkDelete using graphql api
                $deleteMutation = 'mutation bulkDeleteProductVariants($productId: ID!, $variantsIds: [ID!]!) {
                    productVariantsBulkDelete(productId: $productId, variantsIds: $variantsIds) {
                        product {
                            id
                            title
                            productType
                        }
                        userErrors {
                            field
                            message
                        }
                    }
                }';

                $productVariantsBulkDelete = $shop->api()->graph($deleteMutation, [
                    'productId' => $product_id,
                    'variantsIds' => $variant_ids
                ]);

                //log deleted variant
                Log::info('DeleteExistsVariant-------------', ['ids' => $variant_ids, 'deleted variant' => $productVariantsBulkDelete]);
            } catch (Exception $e) {
                Log::info('DeleteExistsVariant -------------', ['error' => $e->getMessage()]);
            }
        } else {
            return;
        }
    }

    public static function getAllVariants($shop, $id)
    {
        $variandId = "gid://shopify/ProductVariant/" . $id;
        $product_id = $shop->products->first()->product_id;
        $variants_response = $shop->api()->rest('GET', '/admin/api/2024-04/products/' . $product_id . '/variants.json');
        $variants = $variants_response['body']['variants'];
        $variants_created_at_20mins_ago = [$variandId];

        foreach ($variants as $variant) {
            $created_at = $variant['created_at'];
            $created_at_timestamp = strtotime($created_at);
            $twenty_mins_ago = strtotime('-20 minutes');

            if ($created_at_timestamp <= $twenty_mins_ago) {
                if ($variant['position'] != 1) {
                    $variants_created_at_20mins_ago[] = "gid://shopify/ProductVariant/" . $variant['id'];
                }
            }
        }

        return $variants_created_at_20mins_ago;
    }
}
