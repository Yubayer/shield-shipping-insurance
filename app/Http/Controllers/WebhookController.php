<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function webhookCartsUpdate(Request $request)
    {
        // return with 200 status code
        log::info("Webhook called carts/update", ["data" => $request->all(), 'headers' => $request->header()]);
        return response()->json(['status' => 'success'], 200);
    }

    public function webhookOrdersCreate(Request $request)
    {
        // return with 200 status code
        log::info("Webhook called orders/create", ["data" => $request->all()]);
        return response()->json(['status' => 'success'], 200);
    }
}
