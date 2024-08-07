<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Osiset\ShopifyApp\Storage\Models\Plan;

use Carbon\Carbon;

use Illuminate\Support\Facades\URL;

class PricingController extends Controller
{
    public function plan(Request $request)
    {
        $shop = User::where('name', $request->shop)->first();
        $plans = Plan::where('is_fixed_plan', true)->get();
        $orders = $shop->orders;

       
       // start of last month
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        // end of last month
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        $revenue = 0;
        $protectedOrders = $orders->where('protection_status', 1)->whereBetween('created_at', [$startDate, $endDate]);
        if($protectedOrders->count() > 0) {
            $revenue = $protectedOrders->sum('protection_price');
        }

        return view('plan-and-pricing.plan', compact(['plans', 'shop', 'revenue']));
    }

    public function createPlan(Request $request) {
        $price = $request->price;

        $planData = [
            "type" => "RECURRING",
            "name" => "Starter",
            "price" => $price,
            "interval" => "EVERY_30_DAYS",
            "capped_amount" => 1000,
            "terms" => "Extra charges are applied based on Protection Revenue",
            "test" => true,
            "trial_days" => 7,
            "on_install" => false,
        ];

        $newPlan = new Plan();
        $newPlan->type = $planData['type'];
        $newPlan->name = $planData['name'];
        $newPlan->price = $planData['price'];
        $newPlan->interval = $planData['interval'];
        $newPlan->capped_amount = $planData['capped_amount'];
        $newPlan->terms = $planData['terms'];
        $newPlan->test = $planData['test'];
        $newPlan->trial_days = $planData['trial_days'];
        $newPlan->on_install = $planData['on_install'];
        $newPlan->shop_domain = $request->shop;

        $newPlan->save();

        $url = URL::tokenRoute('billing', ['plan' => $newPlan->id]);

        return redirect($url);

        // return this url to the front end
        return response()->json(['url' => $url]);

    }
}
