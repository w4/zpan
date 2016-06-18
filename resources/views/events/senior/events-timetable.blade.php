@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Approve Events') }} @endsection

@section('content')
    @if($unapproved->count())
        @foreach($unapproved as $event)
            <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
                <div class="mdl-card__supporting-text">
                    <h4>{{ $event->user()->first()->getDisplayName() }}</h4>
                    <p>
                         {{ $event->user()->first()->getDisplayName() }} would like to book event <strong>{{ $event->type->name }}</strong> on <strong>{{ ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$event->day] }}</strong> at <strong>{{ sprintf('%02d:00', $event->hour) }}</strong> in room <a href="https://www.habbo.com/room/{{ $event->room_id }}" class="no-decoration">{{ $event->room_id }}</a>.
                    </p>
                    <p>
                        There are {{ App\Models\Event::where('week', $event->week)->where('year', $event->year)->where('day', $event->day)->where('hour', $event->hour)->where('id', '<>', $event->id)->count() }} other people who would like this slot.
                    </p>
                </div>

                <div class="mdl-card__actions mdl-card--border">
                    <form action="{{ route('dashboard::senior-events::approve', $event->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
                            <i class="material-icons">check</i> {{ _('Approve') }}
                        </button>
                    </form>

                    <form action="{{ route('dashboard::senior-events::deny', $event->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
                            <i class="material-icons">close</i> {{ _('Deny') }}
                        </button>
                    </form>

                    <div class="mdl-layout-spacer"></div>

                    <strong>{{ $event->created_at->diffForHumans() }}</strong>
                </div>
            </div>
        @endforeach
    @else
        <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
            <div class="mdl-card__supporting-text">
                <h4>{{ _('All Done!') }}</h4>
                <p>{{ _('There are no events awaiting your viewing.') }}</p>
            </div>
        </div>
    @endif
@endsection
