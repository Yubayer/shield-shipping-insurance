@php

    use Carbon\Carbon;
    $all_orders = $orders;

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

    function generateGraphData1($startTime, $endTime, $all_orders)
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
        for ($i = 0; $i < 24; $i++) {
            $startTime = $startOfDay->copy();
            $endTime = $startOfDay->copy()->addHours(1);
            $totalOrdersLabel[] = $startTime->format('Y-m-d\TH:i:s.u\Z');
            $chartData = generateGraphData1($startTime, $endTime, $all_orders);

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
            $chartData = generateGraphData1($startDate, $endDate, $all_orders);
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
            $chartData = generateGraphData1(now()->parse($label), now()->parse($label)->addDay(3), $all_orders);
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
            $chartData = generateGraphData1(now()->parse($label), now()->parse($label)->addWeek(), $all_orders);
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
            $chartData = generateGraphData1(now()->parse($label), now()->parse($label)->addMonth(), $all_orders);
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
            $chartData = generateGraphData1(now()->parse($label), now()->parse($label)->addDay(), $all_orders);
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
        <div class="card-body">
            <h5 class="card-title">Reports 1 <span>/{{ $date }}</span></h5>

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
                                show: true,
                            },
                        },
                        markers: {
                            size: 1,
                        },
                        colors: @json($colors),
                        fill: {
                            type: "gradient",
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.3,
                                opacityTo: 0.4,
                                stops: [0, 90, 100]
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2,
                            opacity: 1
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
                            }
                        },
                        //remove fraction value from y-axis
                        yaxis: {
                            labels: {
                                formatter: function(val, opts) {
                                    let index = 0;
                                    if (opts) {
                                        index = opts.seriesIndex;
                                    }
                                    let val_label = '';
                                    if (index === 0) {
                                        val_label = 'Total Orders';
                                    } else if (index === 1) {
                                        val_label = 'Protected Orders';
                                    } else if (index === 2) {
                                        val_label = 'Revenue';
                                    } else if (index === 3) {
                                        val_label = 'Rate';
                                    }

                                    if (val_label === 'Rate') {
                                        return val.toFixed(2) + '%';
                                    } else if (val_label === 'Revenue') {
                                        console.log(val);
                                        return ': $' + val.toFixed(2);
                                    } else {
                                        return ': ' + val.toFixed(0);
                                    }
                                },
                            },
                        },
                        
                    }).render();
                });
            </script>
            <!-- End Line Chart -->

        </div>

    </div>
</div><!-- End Reports -->
