<?php
namespace App\Helper;

class ChartData
{
    public static function generate($startTime, $endTime, $all_orders)
    {
        $orders__ = $all_orders->where('created_at', '>=', $startTime)->where('created_at', '<', $endTime);
        $protectOrders__ = $orders__->where('protection_status', 1);
        $totalOrdersData = $orders__->count();
        $protectedOrderData = $protectOrders__->count();
        $protectionRevenue = number_format($protectOrders__->sum('protection_price'), 1);
        if ($totalOrdersData > 0) {
            $optInRate = number_format(($protectedOrderData / $totalOrdersData) * 100, 1);
        } else {
            $optInRate = 0;
        }

        return [
            'total_orders' => $totalOrdersData,
            'protected_orders' => $protectedOrderData,
            'rate' => $optInRate,
            'revenue' => $protectionRevenue,
        ];
    }
}
 