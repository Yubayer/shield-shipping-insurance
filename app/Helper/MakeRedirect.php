<?php
namespace App\Helper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

// user model
use App\Models\User;


class MakeRedirect
{
    public static function getRedirectRoute($routeName, $params = []) {
        // dd(Request::all(), "dfd", $routeName, $params);
        $shop = Auth::user();
        // dd($shop);
        $shopDomain = str_replace(".myshopify.com", "", $shop->getDomain()->toNative());
        // dd($shopDomain);
        $path = URL::tokenRoute($routeName, $params);
        //replace http with https
        $path = str_replace("http", "https", $path);
        $path .= "&host=" . base64_encode("admin.shopify.com/store/" . $shopDomain);
        // dd($path);
        return $path;
    }
    
    public static function getHost() {
        $shop = Auth::user();
        $shopDomain = str_replace(".myshopify.com", "", $shop->getDomain()->toNative());
        dd($shopDomain);
        return ("admin.shopify.com/store/" . $shopDomain);
    }
}