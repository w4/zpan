<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
</head>

<body id="app-layout" data-root="{{ route('index', [], false) }}">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        @include('layouts.nav')

        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="zpan-container">
                <div id="error-container">
                    @include('layouts.message')
                </div>

                <div id="content">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <div id="toast" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>

    <script>
        var PUSHER_APP_KEY = "{{ env('PUSHER_KEY') }}";
        var PUSHER_CLUSTER = "{{ env('PUSHER_CLUSTER') }}";
        var PUSHER_ENDPOINT = "{{ route('auth::pusher') }}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var IS_DJ = {{ auth()->user()->is(App\Models\Group::RADIO_DJ, App\Models\Group::GUEST_DJ) ? 'true' : 'false' }};
    </script>
    <script src="{{ elixir('js/main.js') }}"></script>
</body>
</html>
