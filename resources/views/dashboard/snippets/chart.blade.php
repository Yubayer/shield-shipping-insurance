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

    dump($dateType);

    $totalOrdersLabel = [];
    for ($i = 0; $i < 24; $i++) {
        $totalOrdersLabel[] = sprintf('%02dhrs', $i);
    }
    $totalOrdersLabel = [];
    $totalOrdersData = [];
    $protectedOrderData = [];

    $optInRate = [];
    $protectionRevenue = [];

    function generateGraphData($startTime, $endTime, $all_orders)
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

    if ($dateType == 'day') {
        $startOfDay = $start_date->copy()->startOfDay();
        for ($i = 0; $i < 7; $i++) {
            $startTime = $startOfDay->copy();
            $endTime = $startOfDay->copy()->addHours(4);
            $totalOrdersLabel[] = $startTime->format('Y-m-d\TH:i:s.u\Z');
            $chartData = generateGraphData($startTime, $endTime, $all_orders);

            $totalOrdersData[] = $chartData['total_orders'];
            $protectedOrderData[] = $chartData['protected_orders'];
            $optInRate[] = $chartData['rate'];
            $protectionRevenue[] = $chartData['revenue'];
            $startOfDay->addHours(4);
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
            $startOfMonth->addDay();
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


<div class="col-12">
    <div class="card">

        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
        </div>

        <div class="card-body">
            <h5 class="card-title">Reports <span>/{{ $date }}</span></h5>

            <!-- Line Chart -->
            <div id="reportsChart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    let chartType = "{{ $chartType }}";
                    new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                            name: 'Total Orders',
                            data: @json($totalOrdersData),
                        }, {
                            name: 'Protected Orders',
                            data: @json($protectedOrderData),

                        }, {
                            name: 'Revenue',
                            data: @json($protectionRevenue)
                        }, {
                            name: 'Rate',
                            data: @json($optInRate)
                        }],
                        chart: {
                            height: 450,
                            type: chartType,
                            toolbar: {
                                show: true
                            },
                        },
                        markers: {
                            size: 4
                        },
                        colors: ['#FF0000', '#0000FF', '#008000', '#FFA500'],
                        fill: {
                            type: "gradient",
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.3,
                                opacityTo: 0.4,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            type: 'datetime',
                            categories: @json($totalOrdersLabel),
                            // categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z",
                            //     "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z",
                            //     "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z",
                            //     "2018-09-19T06:30:00.000Z"
                            // ]
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy HH:mm',
                            },
                        }
                    }).render();
                });
            </script>
            <!-- End Line Chart -->

        </div>

    </div>
</div><!-- End Reports -->
