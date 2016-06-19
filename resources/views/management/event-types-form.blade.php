@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Add Request Line Ban') }} @endsection
@section('page-title')Management <i class="material-icons">chevron_right</i> Event Types <i
    class="material-icons">chevron_right</i> Add @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Add Event Type') }}</h4>

            <p>{{ _('Here you can new event types for the events staff to use.') }}</p>

            <form role="form" method="POST" action="{{ route('dashboard::management::event-type.add') }}">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <fieldset
                    class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' : '' }}">
                    <input type="text" class="mdl-textfield__input" id="name" name="name"
                           value="{{ old('name') }}">
                    <label class="mdl-textfield__label" for="name">Name</label>
                    @if ($errors->has('name'))
                        <span class="mdl-textfield__error">{{ $errors->first('name') }}</span>
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
