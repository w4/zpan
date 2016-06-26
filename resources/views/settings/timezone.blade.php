@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Update Timezone') }} @endsection
@section('page-title')Settings <i class="material-icons">chevron_right</i> Update Timezone @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp" style="overflow: visible">
        <div class="mdl-card__supporting-text" style="overflow: visible">
            <h4>{{ _('Update Timezone') }}</h4>

            <p>{{ _('Here you can update the timezone used throughout the panel, this will reflect on every time displayed on the panel.') }}</p>

            <form role="form" method="POST" action="{{ route('dashboard::settings::timezone.update') }}">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <fieldset
                    class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fullwidth {{ $errors->has('timezone') ? 'is-invalid' : '' }}">
                    <input class="mdl-textfield__input" name="timezone" type="text" id="timezone"
                           value="{{ old('timezone') }}" required readonly>

                    <label for="timezone">
                        <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                    </label>

                    <label for="timezone" class="mdl-textfield__label">Timezone</label>

                    <ul for="timezone" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                        @foreach($timezones as $timezone)
                            <li class="mdl-menu__item">{{ $timezone }}</li>
                        @endforeach
                    </ul>

                    @if ($errors->has('timezone'))
                        <span class="mdl-textfield__error">{{ $errors->first('timezone') }}</span>
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
