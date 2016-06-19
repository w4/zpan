@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Event Types') }} @endsection
@section('page-title')Management <i class="material-icons">chevron_right</i> Event Types @endsection

@section('content')
    <div class="table-responsive">
        {!! $types->links() !!}

        <table class="mdl-data-table mdl-js-data-table mdl-cell mdl-cell--12-col mdl-shadow--2dp">
            <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">#</th>
                <th class="mdl-data-table__cell--non-numeric">Name</th>
                <th class="mdl-data-table__cell--non-numeric">Amount of Events</th>
                <th class="mdl-data-table__cell--non-numeric">Added</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($types as $type)
                <tr>
                    <td class="mdl-data-table__cell--non-numeric">{{ $type->id }}</td>
                    <td class="mdl-data-table__cell--non-numeric">{{ $type->name }}</td>
                    <td class="mdl-data-table__cell--non-numeric">{{ App\Models\Event::whereEventTypeId($type->id)->count() }}</td>
                    <td class="mdl-data-table__cell--non-numeric">{{ $type->created_at->diffForHumans() }}</td>
                    <td>
                        <form action="{{ route('dashboard::management::event-type.delete', $type->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}

                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                <i class="material-icons delete">delete forever</i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $types->links() !!}
    </div>

    <a class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" id="add"
       href="{{ route('dashboard::management::event-type.form') }}">
        <i class="material-icons">add</i>
    </a>
@endsection
