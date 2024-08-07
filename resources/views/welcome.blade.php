@extends('layouts.app')

@section('heading')
    {{-- <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            </ol>
        </nav>
    </div> --}}
@endsection

@section('content')
    @php
        if (!empty($shop->settings)) {
            $rules_raw = $shop->settings->rules;
            if ($rules_raw && !empty($rules_raw)) {
                $rules = json_decode($rules_raw, true);
            } else {
                $rules = [];
            }
        } else {
            $rules = [];
        }

    @endphp

    <section class="section">

        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> Welcome to Shield Order Protection || {{ $shop->name }}</h4>
                {{-- <h3 class="card-title">
                    @php
                        $currentTime = date('H:i');
                        $greeting = '';

                        if ($currentTime >= '06:00' && $currentTime < '12:00') {
                            $greeting = 'Good morning';
                        } else {
                            $greeting = 'Good night';
                        }
                    @endphp

                    {{ $greeting }}
                </h3> --}}

                <p class="card-text">Shield offers to safeguard your purchases. With our app, you can shop with confidence,
                    knowing that your orders are protected from damage, loss, or theft. Enjoy 24/7 support—because your
                    peace of mind is our priority
                </p>

            </div>
        </div>

        @if (isset($rules) && count($rules) > 0)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Protection Rules </h4>
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Rule Min Value</th>
                                <th scope="col">Rule Max Value</th>
                                <th scope="col">Rule Type</th>
                                <th scope="col">Protection Const</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rules as $rule)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $rule['from'] }}</td>
                                    <td>{{ $rule['to'] }}</td>
                                    <td>{{ $rule['type'] }}</td>
                                    <td>{{ $rule['value'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
@endsection
