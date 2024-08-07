<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Osiset\ShopifyApp\Actions\AuthenticateShop;
use Osiset\ShopifyApp\Exceptions\MissingAuthUrlException;
use Osiset\ShopifyApp\Exceptions\MissingShopDomainException;
use Osiset\ShopifyApp\Exceptions\SignatureVerificationException;
use Osiset\ShopifyApp\Messaging\Events\ShopAuthenticatedEvent;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Util;

use App\Events\ShopInstalledEvent;
use App\Models\Shop;

class AuthController extends Controller
{
    public function authenticate(Request $request, AuthenticateShop $authShop)
    {
        if ($request->missing('shop') && !$request->user()) {
            // One or the other is required to authenticate a shop
            throw new MissingShopDomainException('No authenticated user or shop domain');
        }

        // Get the shop domain
        $shopDomain = $request->has('shop')
            ? ShopDomain::fromNative($request->get('shop'))
            : $request->user()->getDomain();

        // If the domain is obtained from $request->user()
        if ($request->missing('shop')) {
            $request['shop'] = $shopDomain->toNative();
        }

        // Run the action
        [$result, $status] = $authShop($request);

        if ($status === null) {
            // Show exception, something is wrong
            throw new SignatureVerificationException('Invalid HMAC verification');
        } elseif ($status === false) {
            if (!$result['url']) {
                throw new MissingAuthUrlException('Missing auth url');
            }

            $shopDomain = $shopDomain->toNative();
            $shopOrigin = $shopDomain ?? $request->user()->name;

            event(new ShopAuthenticatedEvent($result['shop_id']));

            return View::make(
                'shopify-app::auth.fullpage_redirect',
                [
                    'apiKey' => Util::getShopifyConfig('api_key', $shopOrigin),
                    'appBridgeVersion' => Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '',
                    'authUrl' => $result['url'],
                    'host' => $request->get('host'),
                    'shopDomain' => $shopDomain,
                ]
            );
        } else {
            // Go to home route
            $shop_domain = $shopDomain->toNative();
            ShopInstalledEvent::dispatch($shop_domain);

            return Redirect::route(
                Util::getShopifyConfig('route_names.home'),
                [
                    'shop' => $shopDomain->toNative(),
                    'host' => $request->get('host'),
                ]
            );
        }
    }
}
