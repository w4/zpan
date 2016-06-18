@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title')Home @endsection
@section('page-title')ZPAN @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Dashboard') }}</h4>

            <p>You are logged in.</p>
        </div>
    </div>
@endsection
