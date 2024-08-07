<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card">
        <div class="card-body">
            <h5 class="card-title">Opt In Rate <span>| {{ $date }}</span></h5>

            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-percent"></i>
                </div>
                <div class="ps-3">
                    <h6>{{ number_format($rate, 2) }}%</h6>
                    {{-- <span class="text-success small pt-1 fw-bold">{{ $optInRate }}%</span> <span
                        class="text-muted small pt-2 ps-1">increase</span> --}}

                </div>
            </div>
        </div>

    </div>
</div><!-- End Sales Card -->
