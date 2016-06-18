@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Requests') }} @endsection

@section('content')
    {!! $requests->links() !!}

    @if($requests->count())
        @foreach($requests as $request)
            <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
                <div class="mdl-card__supporting-text">
                    <h4>{{ $request->name }}</h4>
                    <p>{{ $request->request }}</p>
                </div>

                <div class="mdl-card__actions mdl-card--border">
                    <form action="{{ route('dashboard::dj::requests.delete', $request->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
                            <i class="material-icons">check</i> {{ _('Done') }}
                        </button>
                    </form>

                    <div class="mdl-layout-spacer"></div>

                    <strong>{{ $request->created_at->diffForHumans() }} &mdash; {{ $request->ip_address }}</strong>
                </div>
            </div>
        @endforeach
    @else
        <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
            <div class="mdl-card__supporting-text">
                <h4>{{ _('All Done!') }}</h4>
                <p>{{ _('There are no requests awaiting your viewing. Maybe try mentioning out the request line on air!') }}</p>
            </div>
        </div>
    @endif

    {!! $requests->links() !!}
@endsection
