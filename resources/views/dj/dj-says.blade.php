@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Update DJ Says') }} @endsection
@section('page-title')DJ <i class="material-icons">chevron_right</i> Update DJ Says @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Update DJ Says') }}</h4>

            <p>{{ _('Here you can update the "DJ Says" displayed on our main site. It can take up to 5 minutes to reflect on the main site. Please ensure that you are the current DJ before updating.') }}</p>

            <p>{!! __('The current DJ says is %s', "<strong>{$current}</strong>") !!}</p>

            <form role="form" method="POST" action="{{ route('dashboard::dj::says.post') }}">
                {{ csrf_field() }}

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('msg') ? 'is-invalid' : '' }}">
                    <input id="msg" type="text" class="mdl-textfield__input" name="msg" value="{{ old('msg') }}"
                           maxlength="200">
                    <label class="mdl-textfield__label" for="msg">{{ _('DJ Says') }}</label>
                    @if ($errors->has('msg'))
                        <span class="mdl-textfield__error">{{ $errors->first('msg') }}</span>
                    @endif
                </fieldset>

                <fieldset class="form-group">
                    <button type="submit"
                            class="mdl-button mdl-button--raised mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                        <i class="fa fa-btn fa-pencil-square-o"></i> {{ _('Update') }}
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
