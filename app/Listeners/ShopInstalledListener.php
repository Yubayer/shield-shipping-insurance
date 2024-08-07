<?php

namespace App\Listeners;

use App\Events\ShopInstalledEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;

use App\Models\User;

//import Helper
use App\Helper\UpdateShopData;
use App\Helper\AppUrlMetafiledSync;
use App\Helper\AppSettingsMetafieldSync;

class ShopInstalledListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ShopInstalledEvent $event): void
    {
        Log::info('ShopInstalledListener:handle', ['shop_domain' => $event->shop_domain]);
        
        if ($event->shop_domain) {
            $domain = $event->shop_domain;
            $shop = User::where('name', $domain)->first();

            if (!$shop) {
                return;
            }
            
            //update shop data 
            // create metafield and product
            UpdateShopData::updateShopData($shop);

            //sync app url metafield
            AppUrlMetafiledSync::appUrlSync($shop);

            //sync app settings metafield
            AppSettingsMetafieldSync::appSettingsSync($shop, false);
        }
    }
}
