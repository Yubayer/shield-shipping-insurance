<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong class="app-activate-status text-dark">
            Your App is currently : 
            @if ($shop->status)
                <span class="text-primary">Activated</span>
            @else
                <span class="text-primary">Deactivated</span>
            @endif
        </strong>

        <div class="d-flex justify-content-between">
            <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-right: 8px;">Deactivate</label>
            <div class="form-check form-switch">
                <input {{ $shop->status ? 'checked' : '' }} class="form-check-input app-activate-button"
                    type="checkbox" role="switch" id="flexSwitchCheckDefault">
            </div>
            <label class="form-check-label text-bold" for="flexSwitchCheckDefault">Activate</label>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        // app activate button
        if (document.querySelector(".app-activate-button")) {
            document.querySelector(".app-activate-button").addEventListener('change', function(e) {
                e.preventDefault();
                let button = e.target;
                let status = button.getAttribute('data-status');
                let message = status === '1' ? 'Deactivating...' : 'Activating...';
                button.innerHTML = message;
                button.setAttribute('disabled', 'disabled');

                fetch('{{ route('settings.api.app-activate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            domain: '{{ $domain }}'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("data: --------------------", data);
                        // Handle the response data
                        toastr.success(data.message)
                        let span = document.querySelector('.app-activate-status span');
                        if (data.status === '1') {
                            button.innerHTML = 'Deactivate';
                            span.innerHTML = 'Activated';
                        } else {
                            button.innerHTML = 'Activate';
                            span.innerHTML = 'Deactivated';
                        }

                        button.removeAttribute('disabled');
                    })
                    .catch(error => {
                        console.error('Error: ------------', error);
                        // Handle the error
                    });
            });
        }
    </script>
@endpush
