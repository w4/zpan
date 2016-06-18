@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Add Request Line Ban') }} @endsection
@section('page-title')Administration <i class="material-icons">chevron_right</i> Request Line Bans <i class="material-icons">chevron_right</i> Add @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Add IP Ban') }}</h4>

            <p>{{ _('Here you can ban IP addresses from submitting requests through the request line. Please enter a well-formed IP address below (ie. 94.213.39.123) and submit.') }}</p>

            <form role="form" method="POST" action="{{ route('dashboard::admin::request-ban.ban') }}">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('ip') ? 'is-invalid' : '' }}">
                    <input type="text" class="mdl-textfield__input" id="ip" name="ip"
                           value="{{ old('ip') }}">
                    <label class="mdl-textfield__label" for="ip">IP Address</label>
                    @if ($errors->has('ip'))
                        <span class="mdl-textfield__error">{{ $errors->first('ip') }}</span>
                    @endif
                </fieldset>

                <fieldset class="form-group">
                    <button type="submit"
                            class="mdl-button mdl-button--raised mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                        <i class="fa fa-btn fa-plus"></i> {{ _('Save') }}
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
