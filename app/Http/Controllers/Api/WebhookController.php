<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//import log
use Illuminate\Support\Facades\Log;

//use job
use App\Jobs\AppUninstalledJob;
use App\Jobs\OrdersPaidJob;
//use orders paid job


class WebhookController extends Controller
{
    public function webhookAppUninstalled(Request $request)
    {
        //get request data
        $data = $request->all();
        $domain = $request->header('x-shopify-shop-domain');

         //log data
         Log::info('webhookAppUninstalled------', ['data' => $data, 'domain' => $domain]);

        //dispatch app uninstalled job
        AppUninstalledJob::dispatch($domain, $data);

        return response()->json(['status' => 'success'], 200);
    }

    public function webhookOrdersPaid(Request $request)
    {
        //get request data
        $data = $request->all();
        $domain = $request->header('x-shopify-shop-domain');

        //dispatch order paid job
        OrdersPaidJob::dispatch($domain, $data);

        //log data
        Log::info('webhookOrdersPaid------', ['data' => $data, 'domain' => $domain]);

        return response()->json(['status' => 'success'], 200);
    }

    public function webhookCartsUpdate(Request $request)
    {
        //get request data
        $data = $request->all();
        $domain = $request->header('x-shopify-shop-domain');
        

        //log data
        Log::info('webhookCartsUpdate------', ['data' => $data, 'domain' => $domain]);

        return response()->json(['status' => 'success'], 200);
    }
}
