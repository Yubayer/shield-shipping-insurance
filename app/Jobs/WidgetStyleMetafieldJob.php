<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//use log
use Illuminate\Support\Facades\Log;

class WidgetStyleMetafieldJob implements ShouldQueue
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
        $shop_id = $shop->shop->shop_id;
        $widgets = $shop->widget;
        $styles = $widgets->styles;

        if(!$styles){
            $styles = [];
        }

        $json_style = json_encode($styles);

        //create shop metafiled for widget style
        $metafieldsData = [
            "shop_id" => $shop_id,
            "namespace" => "protection",
            "key" => "styles",
            "value" => $json_style,
            "type" => "json",
            "ownerId" => $shop->shop->admin_graphql_api_id,
        ];

        //create shop metafield 
        $responseData = $shop->api()->rest('POST', '/admin/api/2024-04/metafields.json', [
            'metafield' => $metafieldsData
        ]);


        //log json style
        Log::info('WidgetStyleMetafieldJob - updated style ------------', ['json style' => $responseData]);

    }
}
