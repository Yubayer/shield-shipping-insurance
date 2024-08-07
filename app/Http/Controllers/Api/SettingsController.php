<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop;
use App\Models\Settings;
use App\Models\User;

use App\Helper\AppSettingsMetafieldSync;
use App\Helper\AppActivationMetafieldSync;


class SettingsController extends Controller
{
    public function AppActivate(Request $request)
    {
        try {
            $authShop = User::where('name', $request->domain)->first();
            $shop = Shop::where('domain', $request->domain)->first();
            $shop->status = !$shop->status;
            $shop->save();

            $settings = Settings::where('shop_id', $shop->shop_id)->first();
            $settings->status = !$shop->status;
            $settings->save();

            AppActivationMetafieldSync::appActivationSync($authShop, $shop->status);

            if ($shop->status == true) {
                $updatedStatus = '1';
                $message = 'App activated successfully';
            } else {
                $updatedStatus = '0';
                $message = 'App deactivated successfully';
            }

            return response()->json([
                'status' => $updatedStatus,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Failed to activate/deactivate app',
            ]);
        }
    }

    public function otpInModalOrCheckbox(Request $request)
    {
        try {
            $shop = Shop::where('domain', $request->domain)->first();
            $authShop = User::where('name', $request->domain)->first();

            $settings = Settings::where('shop_id', $shop->shop_id)->first();
            $settings->is_modal = !$settings->is_modal;
            $settings->save();

            AppSettingsMetafieldSync::appSettingsSync($authShop, $settings->is_modal);

            if ($settings->is_modal == true) {
                $updatedStatus = '1';
                $message = 'Pop-up activated successfully';
            } else {
                $updatedStatus = '0';
                $message = 'Checkbox activated successfully';
            }

            return response()->json([
                'status' => $updatedStatus,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Failed to activate/deactivate app',
            ]);
        }
    }
}
