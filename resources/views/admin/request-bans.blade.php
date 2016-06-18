@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('Request Line Bans') }} @endsection
@section('page-title')Administration <i class="material-icons">chevron_right</i> Request Line Bans @endsection

@section('content')
    <div class="table-responsive">
        {!! $bans->links() !!}

        <table class="mdl-data-table mdl-js-data-table mdl-cell mdl-cell--12-col mdl-shadow--2dp">
            <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">#</th>
                    <th class="mdl-data-table__cell--non-numeric">IP Address</th>
                    <th class="mdl-data-table__cell--non-numeric">Banned By</th>
                    <th class="mdl-data-table__cell--non-numeric">Added</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($bans as $ban)
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">{{ $ban->id }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ $ban->ip_address }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ App\Models\User::find($ban->added_by)->getDisplayName() }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ $ban->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('dashboard::admin::request-ban.unban', $ban->id) }}" method="post">
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

        {!! $bans->links() !!}
    </div>

    <a class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" id="add" href="{{ route('dashboard::admin::request-ban.form') }}">
        <i class="material-icons">add</i>
    </a>
@endsection
