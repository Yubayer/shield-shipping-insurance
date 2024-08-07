@extends('layouts.app')


@section('heading')
    <div class="pagetitle">
        <h1>Settings</h1>
        {{-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Settings</a></li>
                <li class="breadcrumb-item">Settings </li>
                &nbsp; ({{ $domain }})
            </ol>
        </nav> --}}

        @php
            //dump all data
            // if(isset($status)) dump($status);
            // if(isset($message)) dump($message);
            // if(isset($type)) dump($type);
            $type = isset($type) ? $type : 'Success';
        @endphp

        @if (isset($status) && isset($message) && isset($type))
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
        $length = count($rules);
        if ($length > 0) {
            $lastRuleValue = $rules[$length - 1];
            $lastRule = [
                'from' => $lastRuleValue['to'] + 1,
                'to' => $lastRuleValue['to'] + 2,
                'type' => '%',
                'value' => 0,
            ];
        } else {
            $lastRule = ['from' => 0, 'to' => 1, 'type' => '$', 'value' => 0];
        }
    @endphp


    <section class="section">
        {{-- // App Activate Button --}}
        @include('settings.snippets.app-activation', ['shop' => $shop])

        {{-- // Opt In Modal Or Checkbox --}}
        @include('settings.snippets.appearance', ['settings' => $settings])

        {{-- // Rules of protection charge --}}
        <div class="card rules-update--card">
            {{-- <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="text-dark">Rules of protection charge</strong>
                @if ($length >= 0)
                    <button
                        title="{{ $shop->status ? '' : 'Your App is Currently Deactivated!' }}" type="button"
                        class="btn btm-sm btn-warning rules-sunc-button">Sync With
                        Shop</button>
                @endif
            </div> --}}
            <form action="{{ route('settings.rule.create') }}" method="post" class="rules_update_form">

                <input type="hidden" name="domain" value="{{ $domain }}">
                <input type="hidden" name="key" value="updated">
                <input type="hidden" name="category" value="update">
                @sessionToken
                <div class="card-body pt-4 rules--wrapper">
                    @foreach ($rules as $key => $rule)
                        <div class="mb-2 row g-3 rule--field rule--{{ $key }}--field"
                            data-index="{{ $key }}">
                            <div class="col-auto">
                                <label for="rule--from" class="visually-hidden">From</label>
                                <input readonly class="form-control-plaintext rule--from-hidden"
                                    value="From {{ $rule['from'] }}, up to">
                                <input type="hidden" name="from[]" readonly class="form-control-plaintext" id="rule--from"
                                    value="{{ $rule['from'] }}" required>
                            </div>
                            <div class="col-auto col-md-2">
                                <label for="rule--to" class="visually-hidden">To</label>
                                @php
                                    $max = null;

                                @endphp
                                <input type="number" name="to[]" class="form-control" id="rule--to"
                                    value="{{ $rule['to'] }}" min="{{ $rule['from'] + 1 }}"
                                    @if ($key < $length - 1) max="{{ $rules[$key + 1]['from'] - 1 }}" 
                                        placeholder="Up To <= {{ $rules[$key + 1]['from'] - 1 }}" @endif
                                    required>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" name="type[]" aria-label="Rule Charge" required>
                                    <option value="$" @if ($rule['type'] == '$') selected @endif>$ (Fixed)
                                    </option>
                                    <option value="%" @if ($rule['type'] == '%') selected @endif>% (Percent)
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="rule--value" class="visually-hidden">Value</label>
                                <input type="number" name="value[]" class="form-control" id="rule--value"
                                    placeholder="Charge Value" min="0" required value="{{ $rule['value'] }}"
                                    step=".1">
                            </div>
                            @if ($key == ($length - 1))
                                <div class="col-auto">
                                    <button type="button"
                                        class="btn btn-outline-danger rule--delete rule--{{ $key }}--delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary mr-4" data-bs-toggle="modal"
                        data-bs-target="#createRulesModal">
                        Create New Rule
                    </button>
                    @if ($length > 0)
                        <button type="submit" class="btn btn-secondary">Update Rules</button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Vertically centered Modal -->
        <div class="modal fade" id="createRulesModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <strong>Create New Rule</strong>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="rules-create--card" action="{{ route('settings.rule.create') }}" method="post">

                        <input type="hidden" name="key" value="added">
                        <input type="hidden" name="domain" value="{{ $domain }}">
                        <input type="hidden" name="category" value="create">
                        @sessionToken

                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-auto w-25">
                                    <label for="rule--from" class="visually-hidden">From</label>
                                    <input readonly class="form-control-plaintext" id="rule--from-hidden"
                                        value="From {{ $lastRule['from'] }}, up to">
                                    <input type="hidden" name="from" readonly class="form-control-plaintext"
                                        id="rule--from" value="{{ $lastRule['from'] }}" required>
                                </div>
                                <div class="col-auto w-25">
                                    <label for="rule--to" class="visually-hidden">To</label>
                                    <input type="number" name="to" class="form-control" id="rule--to"
                                        placeholder="Up To > {{ $lastRule['to'] }}" value="{{ $lastRule['to'] }}"
                                        min="{{ $lastRule['to'] }}" required>
                                </div>
                                <div class="col-auto w-25">
                                    <select class="form-select" name="type" aria-label="Rule Charge" required>
                                        <option value="$" @if ($lastRule['type'] == '$') selected @endif>$ (Fixed)
                                        </option>
                                        <option value="%" @if ($lastRule['type'] == '%') selected @endif>%
                                            (Percent)</option>
                                    </select>
                                </div>
                                <div class="col-auto w-25">
                                    <label for="rule--value" class="visually-hidden">Value</label>
                                    <input type="number" name="value" class="form-control" id="rule--value"
                                        placeholder="Charge Value" min="0" required step=".1"
                                        value="{{ $lastRule['value'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- End Vertically centered Modal-->
    </section>
@endsection


@push('scripts')
    <script>
        //domcontent loaded
        document.addEventListener('DOMContentLoaded', function() {
            // delete rule
            document.querySelectorAll('.rule--delete').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    let $ruleField = e.target.closest('.rule--field');
                    let form = e.target.closest('form');
                    $ruleField.remove();
                    
                    if(form) form.submit();

                });
            });

            // form submit event. rules-update--card.form
            document.querySelectorAll('.rules-update--card form').forEach(function(element) {
                element.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let form = e.target;
                    let button = form.querySelector('button[type="submit"]');
                    button.innerHTML = 'Updating...';
                    button.setAttribute('disabled', 'disabled');
                    form.submit();
                });
            });


            // form submit event. rules-create--card.form
            document.querySelector('.rules-create--card').addEventListener('submit', function(e) {
                e.preventDefault();
                let form = e.target;
                let button = form.querySelector('button[type="submit"]');
                button.innerHTML = 'Creating...';
                button.setAttribute('disabled', 'disabled');
                form.submit();
            });



        });

        // call api route rules/sync, method post data: domain, and key: sync
        function syncRules(event) {
            let button = event.target;
            button.innerHTML = 'Syncing...';
            button.setAttribute('disabled', 'disabled');

            fetch('{{ route('rules.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        domain: '{{ $domain }}',
                        key: 'add'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("data: --------------------", data);
                    // Handle the response data
                    toastr.success('Rules Successfully Synced')
                    button.innerHTML = 'Sync With Shop';
                    button.removeAttribute('disabled');
                })
                .catch(error => {
                    console.error('Error: ------------', error);
                    // Handle the error
                });
        }

        if (document.querySelector('.rules-sunc-button')) document.querySelector('.rules-sunc-button').addEventListener(
            'click', (e) => syncRules(e));



        //rules update form
        let rulesUpdateForm = document.querySelector('.rules_update_form');
        if (rulesUpdateForm) {
            rulesUpdateForm.addEventListener('input', e => {
                e.preventDefault();
                let thisField = e.target.closest('.rule--field');
                let thisIndex = parseInt(thisField.getAttribute('data-index'));
                let thisFieldToValue = parseFloat(thisField.querySelector('input[name="to[]"]').value);

                thisInput.max = thisFieldToValue + 1;

                let fields = rulesUpdateForm.querySelectorAll('.rule--field');
                fields?.forEach((field) => {
                    let index = parseInt(field.getAttribute('data-index'));

                    if (thisIndex === index) {
                        let nextField = fields[index + 1];
                        let nextFiledToValue = parseFloat(nextField.querySelector('input[name="to[]"]')
                            .value);
                        let nextFieldFromInput = nextField.querySelector('input[name="from[]"]');
                        let nextFiledRulesFromHidden = nextField.querySelector('.rule--from-hidden');
                        nextFieldFromInput.value = thisFieldToValue + 1;
                        nextFiledRulesFromHidden.value = `From ${thisFieldToValue + 1}, up to`;
                        nextFieldFromInput.max = nextFiledToValue + 1;
                        console.log("this value", thisFieldToValue + 1);
                    }
                })

            })
        }
    </script>
@endpush
