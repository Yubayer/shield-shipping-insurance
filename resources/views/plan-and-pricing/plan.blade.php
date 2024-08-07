@extends('layouts.app')

@section('heading')
    <div class="pagetitle">
        <h1>Plan & Pricing</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam, voluptates. Vel saepe veritatis quis nisi, dolore sed sunt adipisci provident rem, laboriosam est velit officiis aspernatur nesciunt aliquid laborum obcaecati!
                </li>
            </ol>
        </nav>
    </div>
@endsection

@php
    $revenue_5_percent = ceil($revenue * 0.05);
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-start align-items-center card-title mb-0 pb-0">
                                        <h4 class="">${{ $plan->price }}</h4>
                                        <span>/ Month</span>
                                    </div>
                                    <strong>+ ${{ $revenue_5_percent }} based on revenue (5%)</strong>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-title">Total: ${{ $plan->price + $revenue_5_percent }}</h2>
                                    <p>{{ $plan->terms }}</p>
                                    <p>{{ $plan->terms }}</p>
                                    <p>{{ $plan->terms }}</p>
                                    <form method="post" action="{{ route('app.plan-create') }}">
                                        @sessionToken
                                        <input type="hidden" name="price" value="{{ $plan->price + $revenue_5_percent }}">
                                        <button class="btn btn-primary">Subscribe</button>
                                    </form>
                                    {{-- <a href="{{ URL::tokenRoute('billing', ['plan' => $plan->id]) }}"
                                        class="btn btn-primary">Subscribe</a> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
