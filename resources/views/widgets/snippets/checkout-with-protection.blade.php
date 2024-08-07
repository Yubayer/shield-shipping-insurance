@php
    // dump('Regular Checkout Button Widget: ', $widget);
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title my-0 py-0">Protection Checkout Button</h3>
    </div>
    <div class="card-body pt-2">
        <form>
            <input type="hidden" name="key" value="protection_checkout_btn">
            <input type="hidden" name="domain" value="{{ $domain }}">
            @sessionToken
            

            @foreach($widget as $key => $style)
                <div class="form-group">
                    <div class="mb-3 row">
                        <label for="{{ $key }}" class="col-sm-5 col-form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                        <div class="col-sm-7">
                            @if($key === 'background_color' || $key === 'text_color' || $key === 'border_color')
                                <input type="color" name="{{ $key }}" class="form-control form-control-color" id="{{ $key }}"
                                    value="{{ $style }}" title="{{ ucfirst($key) }}" required>
                            @elseif($key === 'font_weight')
                                <select name="{{ $key }}" class="form-control w-100" id="{{ $key }}" required>
                                    @for($i = 300; $i <= 900; $i += 100)
                                        <option value="{{ $i }}" @if($style == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            @else
                                <input type="text" name="{{ $key }}" class="form-control w-100" id="{{ $key }}"
                                    value="{{ $style }}" placeholder="{{ $style }}" title="{{ ucfirst($key) }}" required>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="form-group">
                <button type="button" class="btn btn-primary rupdate-widget--btn">Save & Update</button>
            </div>
        </form>
    </div>
</div>