@extends(Request::ajax() ? 'layouts.ajax-main' : 'layouts.main')

@section('title'){{ _('View Connection Info') }} @endsection

@section('content')
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('View Connection Info') }}</h4>

            <p>{{ _('Here you can view the connection info for our radio. Please ensure your information is up-to-date before attempting to connect to the server.') }}</p>

            <p>{{ _('Unlike many fansites, we use up-to-date software. This also means we are using SHOUTcast 2. Don\'t worry, you can still use SAM Broadcaster 2 to connect to the server. Please ensure you paste the password exactly how it is displayed below. If your broadcast software supports it, please select SHOUTcast 2, otherwise you can click SHOUTcast 1.') }}</p>

            <p><strong>{{ _('IMPORTANT:') }}</strong> {{ _('Anybody without their panel username in their broadcast password will be kicked from the radio and will be given a warning. Broadcasting at the wrong bitrate will also result in a warning.') }}</p>
        </div>
    </div>

    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
            <h4>{{ _('Connection Information') }}</h4>
            @if($connection)
                <p>
                    {!! __('IP: %s', "<strong>{$connection->ip}</strong>") !!}<br>
                    {!! __('Port: %s', "<strong>{$connection->port}</strong>") !!}<br>
                    {!! __('Password: %s', '<strong>' . auth()->user()->username . ":{$connection->password}</strong>") !!}<br>
                    {!! __('Bitrate: %s', '<strong>256kbps</strong>') !!}
                </p>
            @else
                <p><strong>{{ _('The connection info is currently unset.') }}</strong></p>
            @endif
        </div>
    </div>
@endsection
