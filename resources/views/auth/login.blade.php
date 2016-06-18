<!DOCTYPE html>
<html lang="en">
<head>
    @section('title'){{ _('Login') }} @endsection
    @include('layouts.head')
</head>

<body id="app-layout" class="app-login">
    <div class="mdl-layout mdl-js-layout">
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-card mdl-shadow--6dp">
                <div class="mdl-card__title mdl-color--teal mdl-color-text--white">
                    <h2 class="mdl-card__title-text">zpan</h2>
                </div>

                <form action="{{ route('auth::login.post') }}" method="post">
                    {{ csrf_field() }}

                    <div class="mdl-card__supporting-text">
                        <div
                            class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <input class="mdl-textfield__input" type="email" id="email" name="email"
                                   value="{{ old('email') }}">
                            <label class="mdl-textfield__label" for="email">Email Address</label>
                            @if ($errors->has('email'))
                                <span class="mdl-textfield__error">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div
                            class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <input class="mdl-textfield__input" type="password" id="password" name="password">
                            <label class="mdl-textfield__label" for="password">Password</label>
                            @if ($errors->has('password'))
                                <span class="mdl-textfield__error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mdl-card__actions mdl-card--border">
                        <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
                            {{ _('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="{{ elixir('js/main.js') }}"></script>
</body>
</html>
