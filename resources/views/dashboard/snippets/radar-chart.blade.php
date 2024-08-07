@php

    use Carbon\Carbon;

    $all_orders = $orders;

    // dump($all_orders->count(),$all_orders);

    $start_date = $start_date ? now()->parse($start_date) : null;
    $end_date = $end_date ? now()->parse($end_date) : null;
    $diff_date = $start_date->diffInDays($end_date);

    // dump($diff_date);
    // dump($all_orders->count());
    // dump($totalOrders);

    $dateType = 'day';

    if ($diff_date == 0) {
        $dateType = 'day';
    } elseif ($diff_date == 6) {
        $dateType = 'week';
    } elseif ($diff_date >= 29 && $diff_date <= 31) {
        $dateType = 'month';
    } elseif ($diff_date >= 58 && $diff_date <= 62) {
        $dateType = '2 months';
    } elseif ($diff_date >= 87 && $diff_date <= 93) {
        $dateType = '3 months';
    } elseif ($diff_date >= 174 && $diff_date <= 186) {
        $dateType = '6 months';
    } elseif ($diff_date >= 360 && $diff_date <= 366) {
        $dateType = 'year';
    } else {
        $dateType = 'custom';
    }

    // dump($dateType);

    $totalOrdersLabel = [];
    for ($i = 0; $i < 24; $i++) {
        $totalOrdersLabel[] = sprintf('%02dhrs', $i);
    }
    $totalOrdersLabel = [];
    $totalOrdersData = [];
    $protectedOrderData = [];

    $optInRate = [];
    $protectionRevenue = [];
    $colors = ['#FF0000', '#0000FF', '#008000', '#FFA500'];

    function generateGraphData($startTime, $endTime, $all_orders)
    {
        $orders__ = $all_orders->where('created_at', '>=', $startTime)->where('created_at', '<', $endTime);
        $protectOrders__ = $orders__->where('protection_status', 1);
        $totalOrdersData = $orders__->count();
        $protectedOrderData = $protectOrders__->count();
        $protectionRevenue = $protectOrders__->sum('protection_price');
        if ($totalOrdersData > 0) {
            $optInRate = number_format(($protectedOrderData / $totalOrdersData) * 100, 2);
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

    if ($dateType == 'day') {
        $startOfDay = $start_date->copy()->startOfDay();
        for ($i = 0; $i < 24; $i=$i+2) {
            $startTime = $startOfDay->copy();
            $endTime = $startOfDay->copy()->addHours(4);
            $totalOrdersLabel[] = sprintf('%02dhrs', $i);
            $chartData = generateGraphData($startTime, $endTime, $all_orders);

            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
            $startOfDay->addHours(1);
        }
    } elseif ($dateType == 'week') {
        //generate week date between start_date and end_date based on previous date
        for ($i = $start_date->day; $i <= $end_date->day; $i++) {
            $startDate = $start_date->copy()->setDay($i);
            $endDate = $start_date->copy()->setDay($i + 1);

            $totalOrdersLabel[] = $startDate->format('Y-m-d');
            $chartData = generateGraphData($startDate, $endDate, $all_orders);
            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
        }
    } elseif ($dateType == 'month') {
        //generate date
        $startOfMonth = $start_date->copy()->startOfMonth();
        $endOfMonth = $end_date->copy()->endOfMonth();
        while ($startOfMonth <= $endOfMonth) {
            $totalOrdersLabel[] = $startOfMonth->format('Y-m-d');
            $startOfMonth->addDay(3);
        }
        foreach ($totalOrdersLabel as $label) {
            $chartData = generateGraphData(now()->parse($label), now()->parse($label)->addDay(), $all_orders);
            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
        }
    } elseif ($dateType == '2 months' || $dateType == '3 months' || $dateType == '6 months') {
        //generate date
        $startOfWeek = $start_date->copy()->startOfWeek();
        $endOfWeek = $end_date->copy()->endOfWeek();
        while ($startOfWeek <= $endOfWeek) {
            $totalOrdersLabel[] = $startOfWeek->format('Y-m-d');
            $startOfWeek->addWeek();
        }
        foreach ($totalOrdersLabel as $label) {
            $chartData = generateGraphData(now()->parse($label), now()->parse($label)->addWeek(), $all_orders);
            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
        }
    } elseif ($dateType == 'year') {
        //generate date
        $startOfYear = $start_date->copy()->startOfYear();
        $endOfYear = $end_date->copy()->endOfYear();
        while ($startOfYear <= $endOfYear) {
            $totalOrdersLabel[] = $startOfYear->format('Y-m');
            $startOfYear->addMonth();
        }
        foreach ($totalOrdersLabel as $label) {
            $chartData = generateGraphData(now()->parse($label), now()->parse($label)->addMonth(), $all_orders);
            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
        }
    } else {
        $totalOrdersLabel = [];
        $startOfMonth = $start_date->copy()->startOfMonth();
        $endOfMonth = $end_date->copy()->endOfMonth();
        while ($startOfMonth <= $endOfMonth) {
            $totalOrdersLabel[] = $startOfMonth->format('Y-m-d');
            $startOfMonth->addDay($diff_date > 20 ? 2 : 1);
        }
        $totalOrdersData = [];
        foreach ($totalOrdersLabel as $label) {
            $chartData = generateGraphData(now()->parse($label), now()->parse($label)->addDay(), $all_orders);
            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
        }
    }

    // dump($totalOrdersLabel, $totalOrdersData);

@endphp


<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reports 2 <span>/{{ $date }}</span></h5>

            <!-- Radar Chart -->
            <div id="radarChart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#radarChart"), {
                        series: [{
                            name: 'Total Orders',
                            data: @json($totalOrdersData)
                        }, {
                            name: 'Protected Orders',
                            data: @json($protectedOrderData)
                        }, {
                            name: 'Opt-in Rate',
                            data: @json($optInRate)
                        }, {
                            name: 'Revenue',
                            data: @json($protectionRevenue)
                        }],
                        colors: @json($colors),
                        chart: {
                            height: 450,
                            type: 'radar',
                        },
                        xaxis: {
                            categories: @json($totalOrdersLabel)
                        },
                        markers: {
                            size: 10
                        },
                    }).render();
                });
            </script>
            <!-- End Radar Chart -->

        </div>
    </div>
</div>
