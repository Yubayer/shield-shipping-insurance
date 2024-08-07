@php
    // 'protected_orders' => $totalProtectionOrders,
    // 'orders' => $totalOrders,
    // 'date' => $date,
    // 'rate' => $optInRate,
    // 'revenue' => $totalProtectionPrice,

    $rc_order = $orders;
    $rc_protected_order = $protected_orders;
    $rc_rate = $rate;
    $rc_revenue = $revenue;

    $series = [$rc_order, $rc_protected_order, $rc_rate, $rc_revenue];
    $labels = ['Total Orders', 'Protected Orders', 'Opt-in Rate', 'Revenue'];
    $colors = ['#FF0000', '#0000FF', '#008000', '#FFA500'];

    // dump($series, $labels, $colors);

@endphp
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reports 3 <span>/{{ $date }}</span></h5>

            <!-- Radial Bar Chart -->
            <div id="radialBarChart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#radialBarChart"), {
                        series: @json($series),
                        chart: {
                            height: 450,
                            type: 'radialBar',
                            toolbar: {
                                show: true
                            }
                        },
                        colors: @json($colors),
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                    },
                                    total: {
                                        show: true,
                                        label: 'Revenue',
                                        formatter: function(w) {
                                            return "${{ $rc_revenue }}";
                                        }
                                    },
                                    style: {
                                        colors: ['#333']
                                    },
                                    //remove % sign from value
                                    value: {
                                        formatter: function(val, opts) {
                                            let rate = "{{ $rc_rate }}";
                                            let value = parseFloat(val).toFixed(2);
                                            if (!rate) {
                                                rate = 0;
                                            } else {
                                                rate = parseFloat(rate).toFixed(2);
                                            }
                                            if (value == rate) {
                                                return rate + '%';
                                            }
                                            return val;
                                        },
                                        show: true,
                                        fontSize: '16px',
                                    }
                                }
                            }
                        },
                        labels: @json($labels)

                    }).render();
                });
            </script>
            <!-- End Radial Bar Chart -->

        </div>
    </div>
</div>
