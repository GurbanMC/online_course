<form action="{{ route('admin.orders.index') }}" class="row align-items-center g-2" role="search" id="orderFilter">
    <div class="col-auto border-end">
        <select class="form-select form-select-sm" name="statistics" id="statistics" onchange="$('form#orderFilter').submit();">
            <option value>@lang('app.statistics')</option>
            @foreach( ['today', 'thisWeek', 'thisMonth'] as $stat )
                <option value="{{ $stat }}" {{ $stat == $statistic ? 'selected' : '' }}>@lang('app.' . $stat)</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-danger">@lang('app.clear') <i class="bi-x"></i></a>
    </div>
    <div class="col-auto">
        <div class="form-check form-control form-control-sm">
            <input class="form-check-input" type="checkbox" value="1" name="trashed" id="trashed" {{ $f_trashed ? 'checked' : '' }} onchange="$('form#orderFilter').submit();">
            <label class="form-check-label" for="trashed">
                @lang('app.trashed')
            </label>
        </div>
    </div>
    @if( config()->has('mysettings.orderPlatforms') )
        <div class="col-auto">
            <select class="form-select form-select-sm" name="platform" id="platform" onchange="$('form#orderFilter').submit();">
                <option value>@lang('app.platform')</option>
                @foreach( config()->get('mysettings.orderPlatforms') as $platform )
                    <option value="{{ $platform['id'] + 1 }}" {{ $platform['id'] == $f_platform - 1 ? 'selected' : '' }}>{{ $platform['name'] }}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if( config()->has('mysettings.orderPayments') )
        <div class="col-auto">
            <select class="form-select form-select-sm" name="payment" id="payment" onchange="$('form#orderFilter').submit();">
                <option value>@lang('app.payment')</option>
                @foreach( config()->get('mysettings.orderPayments') as $payment )
                    <option value="{{ $payment['id'] + 1 }}" {{ $payment['id'] == $f_payment - 1 ? 'selected' : '' }}>@lang('app.' . $payment['name'])</option>
                @endforeach
            </select>
        </div>
    @endif
    @if( config()->has('mysettings.languages') )
        <div class="col-auto">
            <select class="form-select form-select-sm" name="language" id="language" onchange="$('form#orderFilter').submit();">
                <option value>@lang('app.language')</option>
                @foreach( config()->get('mysettings.languages') as $language )
                    <option value="{{ $language['id'] + 1 }}" {{ $language['id'] == $f_language - 1 ? 'selected' : '' }}>@lang('app.' . $language['langName'])</option>
                @endforeach
            </select>
        </div>
    @endif
    @if( config()->has('mysettings.orderStatuses') )
        <div class="col-auto">
            <select class="form-select form-select-sm" name="status" id="status" onchange="$('form#orderFilter').submit();">
                <option value>@lang('app.status')</option>
                @foreach( config()->get('mysettings.orderStatuses') as $status )
                    <option value="{{ $status['id'] + 1 }}" {{ $status['id'] == $f_status - 1 ? 'selected' : '' }}>@lang('app.' . $status['name'])</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col">
        <input class="form-control form-control-sm" type="search" name="q" placeholder="1234 Main St">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-dark btn-sm"><i class="bi-search"></i></button>
    </div>
</form>