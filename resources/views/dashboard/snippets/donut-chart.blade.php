<!-- Donut Chart -->
<div class="card">

    <div class="card-body pb-0">
        <h5 class="card-title">Donut Chart <span>| {{ $date }}</span></h5>

        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        top: '5%',
                        left: 'center'
                    },
                    series: [{
                        name: 'Access From',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '18',
                                fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [{
                                value: 1048,
                                name: 'Search Engine'
                            },
                            {
                                value: 735,
                                name: 'Direct'
                            },
                            {
                                value: 580,
                                name: 'Email'
                            },
                            {
                                value: 484,
                                name: 'Union Ads'
                            },
                            {
                                value: 300,
                                name: 'Video Ads'
                            }
                        ]
                    }]
                });
            });
        </script>

    </div>
</div><!-- End Donut chart -->
