<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//use storage
use Illuminate\Support\Facades\Storage;

//import model
use App\Models\Product;

// use log
use Illuminate\Support\Facades\Log;

// use Helper
use App\Helper\ProtectionProductMetafiledSync;

class ProductCreateJob implements ShouldQueue
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
        $shop = $this->shop;

        if (!$shop) {
            return;
        }

        if ($shop->products->count() == 0) {
            $createdNewProduct = $this->createProduct($shop);

            //log created product
            Log::info('ProductCreateJob-------------createdNewProduct', ['createdNewProduct' => $createdNewProduct]);

            if ($createdNewProduct) {
                $productMetafieldData = json_encode([
                    'product_id' => $createdNewProduct['id'],
                    'product_handle' => $createdNewProduct['handle'],
                    'product_title' => $createdNewProduct['title']
                ]);
                $this->saveProductInDatabase($shop, $createdNewProduct);
                ProtectionProductMetafiledSync::protectionProductSync($shop, $productMetafieldData);
            }
        }
    }

    public function createProduct($shop)
    {
        $product_response = $shop->api()->rest('POST', '/admin/api/2024-04/products.json', [
            'product' => [
                'title' => 'Order Protection',
                'body_html' => '<strong>Order Protection</strong>',
                'vendor' => 'Order Protection',
                'product_type' => 'Order Protection',
                'published' => true
            ]
        ]);
        $product_data = $product_response['body']['product'];

        try {
            // upload image
            $file = Storage::disk('public')->get('images/logo.png');
            $image_base64 = base64_encode($file);

            $image_upload = $shop->api()->rest('POST', '/admin/api/2024-04/products/' . $product_data['id'] . '/images.json', [
                'image' => [
                    'attachment' => $image_base64,
                    'filename' => 'protection-logo.png',
                    'position' => 1,
                    'alt' => 'protection-logo'
                ]
            ]);
        } catch (\Exception $e) {
            Log::info('ProductCreateJob-------------image_upload', ['error' => $e->getMessage()]);
        }

        return $product_data;
    }

    public function saveProductInDatabase($shop, $product_data)
    {
        $newProduct = Product::updateOrCreate(
            ['shop_id' => $shop->shop->shop_id],
            [
                'user_id' => $shop->id,
                'shop_id' => $shop->shop->shop_id,
                'product_id' => $product_data['id'],
                'product_handle' => $product_data['handle'],
                'data' => json_encode($product_data),
                'status' => true,
                'admin_graphql_api_id' => $product_data['admin_graphql_api_id'],
            ]
        );
    }
}
