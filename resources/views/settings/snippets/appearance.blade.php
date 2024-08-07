@push('css')
    <style>
        .opt-in-modal-checkbox {
            background-color: #0d6efd;
            border-color: #0d6efd;
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
        }
    </style>
@endpush

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong class="app-is-modal-status text-dark">
            Appearance Mode :
            @if ($settings->is_modal)
                <span class="text-primary">Pop-up</span>
            @else
                <span class="text-primary">Checkbox</span>
            @endif
        </strong>

        <div class="d-flex justify-content-between">
            <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-right: 8px;">Checkbox</label>
            <div class="form-check form-switch">
                <input {{ $settings->is_modal ? 'checked' : '' }} class="form-check-input opt-in-modal-checkbox"
                    type="checkbox" role="switch" id="flexSwitchCheckDefault">
            </div>
            <label class="form-check-label text-bold" for="flexSwitchCheckDefault">Pop-up</label>
        </div>

        {{-- <button type="submit" class="btn btn-warning opt-in-modal-checkbox">
            @if ($settings->is_modal)
                Checkbox
            @else
                Modal
            @endif
        </button> --}}
    </div>
</div>


@push('scripts')
    <script>
        // opt in modal or checkbox
        if (document.querySelector(".opt-in-modal-checkbox")) {
            document.querySelector(".opt-in-modal-checkbox").addEventListener('change', function(e) {
                e.preventDefault();
                let button = e.target;
                let status = button.getAttribute('data-status');
                let message = status === '1' ? 'Deactivating...' : 'Activating...';
                button.innerHTML = message;
                button.setAttribute('disabled', 'disabled');

                fetch('{{ route('settings.api.is-modal-activate') }}', {
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
                        let span = document.querySelector('.app-is-modal-status span');
                        if (data.status === '1') {
                            button.innerHTML = 'Checkbox';
                            span.innerHTML = 'Pop-up';
                        } else {
                            button.innerHTML = 'Modal';
                            span.innerHTML = 'Checkbox';
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
