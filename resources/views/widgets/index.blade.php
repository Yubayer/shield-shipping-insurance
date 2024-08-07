@extends('layouts.app')


@section('heading')
    <div class="pagetitle">
        <h1>Widgets</h1>
        <nav>
            <ol class="breadcrumb">
                Modal Checkout Styles : ({{ $domain }})
            </ol>
        </nav>
        <button class="btn btn-sm btn-primary widget-sync-with-shop-btn d-none">Sync With Shop</button>
        @if (isset($status))
            @push('scripts')
                <script>
                    let status = '{{ $status }}';
                    let $message = '{{ $message }}';
                    let $type = '{{ $type }}';

                    if (status === 'success') {
                        toastr.success($message)
                    } else {
                        toastr.success($message)
                    }
                </script>
            @endpush
        @endif
    </div>
@endsection

@section('content')
    @php
        if (isset($widgets['styles']) && !empty($widgets['styles'])) {
            $styles = $widgets['styles'];
        } else {
            $styles = [];
        }

    @endphp
    <div class="row">
        <div class="col-md-6">
            @include('widgets.snippets.regular-checkout', [
                'domain' => $domain,
                'widget' => $styles['regular_checkout_btn'],
            ])
        </div>
        <div class="col-md-6">
            @include('widgets.snippets.checkout-with-protection', [
                'domain' => $domain,
                'widget' => $styles['protection_checkout_btn'],
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        //domcontentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            let $updateWidgetsBtns = document.querySelectorAll('.rupdate-widget--btn');
            $updateWidgetsBtns.forEach($updateWidgetsBtn => {
                $updateWidgetsBtn.addEventListener("click", e => {
                    e.preventDefault();
                    let btn = e.target;
                    btn.setAttribute('disabled', true);
                    btn.innerHTML = 'Updating...';

                    let form = e.target.closest('form');
                    let formData = new FormData(form);
                    let data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });
                    // console.log(data);
                    saveWidgetSettings(data).then(result => {
                        console.log("Result: ", result);
                        if (result.status === 'success') {
                            toastr.success("Style Updated Successfully");
                        } else {
                            toastr.error("Failed to update style! Please try again.");
                        }
                    }).finally(() => {
                        btn.removeAttribute('disabled');
                        btn.innerHTML = 'Save & Update';
                    })
                })
            })

            async function saveWidgetSettings(data) {
                let response = await fetch("{{ route('app.widgets.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });
                console.log("response: ", response);
                let result = await response.json();
                return result;
            }
        })

        //widget sync with shop
        let $widgetSyncWithShopBtn = document.querySelector('.widget-sync-with-shop-btn');
        $widgetSyncWithShopBtn.addEventListener('click', e => {
            e.preventDefault();
            let btn = e.target;
            btn.setAttribute('disabled', true);
            btn.innerHTML = 'Syncing...';

            syncWidgetWithShop().then(result => {
                console.log("Result: ", result);
                if (result.status === 'success') {
                    toastr.success(result.message);
                } else {
                    toastr.error(result.message);
                }
            }).finally(() => {
                btn.removeAttribute('disabled');
                btn.innerHTML = 'Sync With Shop';
            });
        })

        async function syncWidgetWithShop() {
            let response = await fetch("{{ route('api.widget.sync') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    domain: '{{ $domain }}'
                })
            });
            let result = await response.json();
            return result;
        }
    </script>
@endpush
