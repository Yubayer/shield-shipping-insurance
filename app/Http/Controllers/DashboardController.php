<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//user mode.
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {

        
        // $start_date = now()->startOfDay();
        $start_date = now()->startOfDay();
        $end_date = now()->endOfDay();
        $data = $this->dateFilter($request->shop, $start_date, $end_date);

        // dump($start_date, $end_date);
        return view('dashboard.index', $data);
    }

    public function dashboardFilter(Request $request)
    {
        $data = $this->dateFilter($request->shop, $start_date, $end_date);
        return view('dashboard.index', $data);
    }

    public function dateFilter($domain, $start_date, $end_date)
    {
        $sd = $start_date;
        $ed = $end_date;

        $shop = User::where('name', $domain)->first();
        $orders = $shop->orders();

        // start_day and end_day convert to carbon instance
        $start_date = $start_date ? now()->parse($start_date)->setTimezone(config('app.timezone')) : null;
        $end_date = $end_date ? now()->parse($end_date)->setTimezone(config('app.timezone')) : null;
        
        $end_date = $end_date->addDay();

        // dump($start_date, $end_date);

        if ($start_date && $end_date) {
            $orders = $orders->whereBetween('created_at', [$start_date, $end_date]);
        }

        $orders = $orders->get();

        // dump($orders);

        $totalOrders = $orders->count();
        $protectionOrder = $orders->where('protection_status', 1);
        $totalProtectionOrders = $protectionOrder->count();
        $optInRate = $totalOrders > 0 ? ($totalProtectionOrders / $totalOrders) * 100 : 0;
        $totalProtectionPrice = $protectionOrder->sum('protection_price');

        $start_date = $sd->format('Y-m-d');
        $end_date = $ed->format('Y-m-d');

        return compact('totalOrders', 'totalProtectionOrders', 'optInRate', 'totalProtectionPrice', 'start_date', 'end_date', 'shop', 'orders');    
    }

    public function dashboardFilterNew(Request $request)
    {
        $shop = User::where('name', $request->shop)->first();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $orders = $shop->orders();


        // start_day and end_day convert to carbon instance
        $start_date = $start_date ? now()->parse($start_date)->setTimezone(config('app.timezone')) : null;
        $end_date = $end_date ? now()->parse($end_date)->setTimezone(config('app.timezone')) : null;
        
        $end_date = $end_date->addDay();

        // dump($start_date, $end_date);

        if ($start_date && $end_date) {
            $orders = $orders->whereBetween('created_at', [$start_date, $end_date]);
        }

        $orders = $orders->get();

        // dump($orders);

        $totalOrders = $orders->count();
        $protectionOrder = $orders->where('protection_status', 1);
        $totalProtectionOrders = $protectionOrder->count();
        $optInRate = number_format($totalOrders > 0 ? ($totalProtectionOrders / $totalOrders) * 100 : 0, 2);
        $totalProtectionPrice = $protectionOrder->sum('protection_price');

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return view('dashboard.index', compact('totalOrders', 'totalProtectionOrders', 'optInRate', 'totalProtectionPrice', 'start_date', 'end_date', 'shop', 'orders'));
        // return compact('totalOrders', 'totalProtectionOrders', 'optInRate', 'totalProtectionPrice', 'start_date', 'end_date', 'shop', 'orders');        
    }
}
