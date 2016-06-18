@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Update Connection Info') }} @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Update Connection Info') }}</h4>

            <p>{{ _('Here you can update the connection information for our radio. Please ensure your information is up-to-date before updating this page.') }}</p>

            <form role="form" method="POST" action="{{ route('dashboard::admin::connection-info.post') }}">
                {{ csrf_field() }}

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('ip') ? 'is-invalid' : '' }}">
                    <input type="text" class="mdl-textfield__input" id="ip" name="ip"
                           value="{{ old('ip', $connection ? $connection->ip : '') }}">
                    <label class="mdl-textfield__label" for="ip">IP Address</label>
                    @if ($errors->has('ip'))
                        <span class="mdl-textfield__error">{{ $errors->first('ip') }}</span>
                    @endif
                </fieldset>

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('port') ? 'is-invalid' : '' }}">
                    <input type="number" class="mdl-textfield__input" id="port" name="port"
                           step="1" max="65535" value="{{ old('port', $connection ? $connection->port : '') }}">
                    <label class="mdl-textfield__label" for="port">{{ _('Port') }}</label>
                    @if ($errors->has('port'))
                        <span class="mdl-textfield__error">{{ $errors->first('port') }}</span>
                    @endif
                </fieldset>

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <input type="password" class="mdl-textfield__input" id="password" name="password"
                           value="{{ old('password') }}">
                    <label class="mdl-textfield__label" for="password">{{ _('Password') }}</label>
                    @if ($errors->has('password'))
                        <span class="mdl-textfield__error">{{ $errors->first('password') }}</span>
                    @endif
                </fieldset>

                <fieldset class="form-group">
                    <button type="submit" class="mdl-button mdl-button--raised mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                        <i class="fa fa-btn fa-pencil-square-o"></i> {{ _('Update') }}
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
