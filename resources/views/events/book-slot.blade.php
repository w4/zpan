@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Book an Event') }} @endsection
@section('page-title')Events <i class="material-icons">chevron_right</i> Timetable <i class="material-icons">chevron_right</i> Book @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp" style="overflow: visible">
        <div class="mdl-card__supporting-text" style="overflow: visible">
            <h4>{{ _('Book an Event') }}</h4>

            <form role="form" method="POST" action="{{ route('dashboard::event::timetable.save') }}">
                {{ method_field('put') }}
                {{ csrf_field() }}

                <input type="hidden" name="day" value="{{ Request::get('day') }}">
                <input type="hidden" name="hour" value="{{ Request::get('hour') }}">

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fullwidth {{ $errors->has('event_type') ? 'is-invalid' : '' }}">
                    <input class="mdl-textfield__input" name="event_type" type="text" id="event-type" value="{{ old('event_type') }}" required readonly>

                    <label for="event-type">
                        <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                    </label>

                    <label for="event-type" class="mdl-textfield__label">Type</label>

                    <ul for="event-type" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                        @foreach(App\Models\EventType::all() as $type)
                            <li class="mdl-menu__item">{{ $type->name }}</li>
                        @endforeach
                    </ul>

                    @if ($errors->has('event_type'))
                        <span class="mdl-textfield__error">{{ $errors->first('event_type') }}</span>
                    @endif
                </fieldset>

                <fieldset class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('room_id') ? 'is-invalid' : '' }}">
                    <input id="room_id" type="number" class="mdl-textfield__input" name="room_id" value="{{ old('room_id') }}"
                           pattern="^[\d]{8,8}$">
                    <label class="mdl-textfield__label" for="room_id">{{ _('Room ID (eg. 29384756)') }}</label>

                    @if ($errors->has('room_id'))
                        <span class="mdl-textfield__error">{{ $errors->first('room_id') }}</span>
                    @endif
                </fieldset>

                <fieldset class="form-group">
                    <button type="submit"
                            class="mdl-button mdl-button--raised mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                        <i class="fa fa-btn fa-floppy-o"></i> {{ _('Save') }}
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
