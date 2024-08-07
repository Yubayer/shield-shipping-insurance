@extends('layouts.app')

@section('heading')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        {{-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            </ol>
        </nav> --}}
    </div>
@endsection

@section('content')
    <div class="mt-2 mb-2">
        <div class="p-0 mt-2 mb-4">
            <form class="data--filter--form" method="POST" action="{{ route('filter.dashboard-new') }}">
                <input type="hidden" name="shop" value="{{ $shop->name }}">
                @sessionToken
                <input type="hidden" name="start_date" value="">
                <input type="hidden" name="end_date" value="">
                <div class="dropdown">
                    <button id="reportrange" class="btn btn-secondary dropdown-toggle" type="button"
                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <section class="section dashboard">
        @php
            use Carbon\Carbon;


            if (isset($start_date) && isset($end_date)) {
                $start_date = $start_date;
                $end_date = $end_date;
            } else {
                $start_date = Carbon::today()->format('Y-m-d');
                $end_date = Carbon::today()->format('Y-m-d');
            }

            $today_date = now()->format('Y-m-d');
            $yesterday_date = now()->subDay()->format('Y-m-d');
            $last_7_days_date = now()->subDays(6)->format('Y-m-d');
            $last_30_days_date = now()->subDays(29)->format('Y-m-d');
            $this_month_date = now()->startOfMonth()->format('Y-m-d');
            $last_month_date = now()->subMonth()->startOfMonth()->format('Y-m-d');
            $last_2_months_date = now()->subMonths(2)->startOfMonth()->format('Y-m-d');
            $last_3_months_date = now()->subMonths(3)->startOfMonth()->format('Y-m-d');
            $last_6_months_date = now()->subMonths(6)->startOfMonth()->format('Y-m-d');
            $this_year_date = now()->startOfYear()->format('Y-m-d');
            $last_1_year_date = now()->subYear()->startOfMonth()->format('Y-m-d');

            
            switch (true) {
                case $start_date == $today_date && $end_date == $today_date:
                    $date = 'Today';
                    break;
                case $start_date == $yesterday_date && $end_date == $yesterday_date:
                    $date = 'Yesterday';
                    break;
                case $start_date == $last_7_days_date && $end_date == $today_date:
                    $date = 'Last 7 Days';
                    break;
                case $start_date == $last_30_days_date && $end_date == $today_date:
                    $date = 'Last 30 Days';
                    break;
                case $start_date == $this_month_date && $end_date == $today_date:
                    $date = 'This Month';
                    break;
                case $start_date == $last_month_date && $end_date == $last_month_date:
                    $date = 'Last Month';
                    break;
                case $start_date == $last_2_months_date && $end_date == $last_month_date:
                    $date = 'Last 2 Months';
                    break;
                case $start_date == $last_3_months_date && $end_date == $last_month_date:
                    $date = 'Last 3 Months';
                    break;
                case $start_date == $last_6_months_date && $end_date == $last_month_date:
                    $date = 'Last 6 Months';
                    break;
                case $start_date == $this_year_date && $end_date == $today_date:
                    $date = 'This Year';
                    break;
                case $start_date == $last_1_year_date && $end_date == $last_month_date:
                    $date = 'Last 1 Year';
                    break;
                default:
                    $date = $start_date . ' - ' . $end_date;
                    break;
            }

        @endphp

        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Protection Revenue -->
                    @include('dashboard.snippets.protection-revenue', [
                        'revenue' => $totalProtectionPrice,
                        'date' => $date,
                    ])

                    {{-- opt in rate --}}
                    @include('dashboard.snippets.opt-in-rate', ['rate' => $optInRate, 'date' => $date])

                    {{-- orders with protection --}}
                    @include('dashboard.snippets.order-with-protection', [
                        'orders' => $totalProtectionOrders,
                        'date' => $date,
                    ])

                    {{-- total orders --}}
                    @include('dashboard.snippets.total-orders', [
                        'orders' => $totalOrders,
                        'date' => $date,
                    ])

                    <!-- Reports -->
                    @include('dashboard.snippets.reports', [
                        'protected_orders' => $totalProtectionOrders,
                        'orders' => $orders,
                        'totalOrders' => $totalOrders,
                        'date' => $date,
                        'rate' => $optInRate,
                        'revenue' => $totalProtectionPrice,
                        // 'chartType' => 'area, bar, line, pie, donut, scatter, radar',
                        'chartType' => 'area',
                    ])

                    {{-- radar chart --}}
                    {{-- @include('dashboard.snippets.radar-chart', [
                        'protected_orders' => $totalProtectionOrders,
                        'orders' => $orders,
                        'totalOrders' => $totalOrders,
                        'date' => $date,
                        'rate' => $optInRate,
                        'revenue' => $totalProtectionPrice,
                    ]) --}}
                   
                    {{-- radial Chart --}}
                    {{-- @include('dashboard.snippets.radial-chart', [
                        'protected_orders' => $totalProtectionOrders,
                        'orders' => $totalOrders,
                        'date' => $date,
                        'rate' => $optInRate,
                        'revenue' => $totalProtectionPrice,    
                    ]) --}}

                </div>
            </div>

            <!-- Right side columns -->
            {{-- <div class="col-lg-4">
                @include('dashboard.snippets.donut-chart', [
                    'protected_orders' => $totalProtectionOrders,
                    'orders' => $totalOrders,
                    'date' => $date,
                    'rate' => $optInRate,
                    'revenue' => $totalProtectionPrice,
                ])
            </div> --}}
        </div>
    </section>

    @php
        //  dump($today_date, $start_date, $end_date, $last_7_days_date, $last_30_days_date, $this_month_date);
    @endphp
@endsection


@push('scripts')
    {{-- <script>
        // form submit using javascript
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.date-filter-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let submit_button = this.querySelector('button[type="submit"]');
                submit_button.setAttribute('disabled', 'disabled');
                submit_button.innerHTML = 'Filtering...';
                this.submit();
            });
        });
    </script> --}}

    <script type="text/javascript">
        $(function() {
            var start = moment('{{ $start_date }}');
            var end = moment('{{ $end_date }}');

            // console.log(start, end)

            function cbUpdate(start, end) {
                // console.log(start, end)
                dateHumanReadable(start, end);
                let form = document.querySelector('.data--filter--form');
                form.querySelector('input[name="start_date"]').value = start.format('YYYY-MM-DD');
                form.querySelector('input[name="end_date"]').value = end.format('YYYY-MM-DD');

                //form submit
                form.submit();
            }

            function cb(start, end) {
                // console.log(start, end)
                dateHumanReadable(start, end);
                let form = document.querySelector('.data--filter--form');
                form.querySelector('input[name="start_date"]').value = start.format('YYYY-MM-DD');
                form.querySelector('input[name="end_date"]').value = end.format('YYYY-MM-DD');
            }

            function dateHumanReadable(start, end) {
                if (start.format('MMMM D, YYYY') == moment().format('MMMM D, YYYY')) {
                    $('#reportrange span').html('Today');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(1, 'days').format('MMMM D, YYYY')) {
                    $('#reportrange span').html('Yesterday');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(6, 'days').format('MMMM D, YYYY')) {
                    $('#reportrange span').html('Last 7 Days');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(29, 'days').format('MMMM D, YYYY')) {
                    $('#reportrange span').html('Last 30 Days');
                } else if (start.format('MMMM D, YYYY') == moment().startOf('month').format('MMMM D, YYYY')) {
                    $('#reportrange span').html('This Month');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(1, 'month').startOf('month').format(
                        'MMMM D, YYYY')) {
                    $('#reportrange span').html('Last Month');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(2, 'months').startOf('month').format(
                        'MMMM D, YYYY')) {
                    $('#reportrange span').html('Last 2 Months');
                } else if (start.format('MMMM D, YYYY') == moment().subtract(3, 'months').startOf('month').format(
                        'MMMM D, YYYY')) {
                    $('#reportrange span').html('Last 3 Months');
                } 
                // else if (start.format('MMMM D, YYYY') == moment().subtract(6, 'months').startOf('month').format(
                //         'MMMM D, YYYY')) {
                //     $('#reportrange span').html('Last 6 Months');
                // }
                else if (start.format('MMMM D, YYYY') == moment().startOf('year').format('MMMM D, YYYY')) {
                    $('#reportrange span').html('This Year');
                }
                else if (start.format('MMMM D, YYYY') == moment().subtract(1, 'year').startOf('month').format(
                        'MMMM D, YYYY')) {
                    $('#reportrange span').html('Last 1 Year');
                } else {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 2 Months': [moment().subtract(2, 'months').startOf('month'), moment().subtract(1,
                        'months').endOf('month')],
                    'Last 3 Months': [moment().subtract(3, 'months').startOf('month'), moment().subtract(1,
                        'months').endOf('month')],
                    // 'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().subtract(1,
                    //     'months').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last 1 Year': [moment().subtract(1, 'year').startOf('month'), moment().subtract(1,
                        'months').endOf('month')],
                }
            }, cbUpdate);

            cb(start, end);

        });
    </script>
@endpush
