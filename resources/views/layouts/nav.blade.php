<header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
        <span class="mdl-layout__title">@yield('page-title')</span>

        <div class="mdl-layout-spacer"></div>

        <nav class="mdl-navigation">
            <span id="username">{{ auth()->user()->getDisplayName() }}</span>
            <button id="user-menu-lower-right"
                    class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons">more_vert</i>
            </button>

            <div class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                for="user-menu-lower-right">
                <a class="mdl-menu__item" href="{{ route('dashboard::settings::timezone') }}">Change Timezone</a>
                <span class="mdl-menu__item" data-mdl-disabled>Change Language</span>
                <a class="mdl-menu__item" href="{{ route('auth::logout') }}">Logout</a>
            </div>
        </nav>
    </div>
</header>

<div class="mdl-layout__drawer">
    <nav class="mdl-navigation">
        <a class="mdl-navigation__link {{ is_route('dashboard::home') ? 'is-active' : '' }}"
           href="/">
            <i class="material-icons">home</i> {{ _('Home') }}
        </a>

        @if(auth()->user()->is(App\Models\Group::RADIO_DJ, App\Models\Group::GUEST_DJ, App\Models\Group::HEAD_DJ))
            <div class="zpan-drawer-separator"></div>

            <span class="mdl-navigation__link" href>DJ</span>

            <a class="mdl-navigation__link {{ is_route('dashboard::dj::says') ? 'is-active' : '' }}"
               href="{{ route('dashboard::dj::says') }}">
                <i class="material-icons">mic</i> DJ Says
            </a>

            <a class="mdl-navigation__link {{ is_route('dashboard::dj::connection-info') ? 'is-active' : '' }}"
               href="{{ route('dashboard::dj::connection-info') }}">
                <i class="material-icons">power</i> Connection Info
            </a>

            <a class="mdl-navigation__link {{ is_route('dashboard::dj::timetable') ? 'is-active' : '' }}"
               href="{{ route('dashboard::dj::timetable') }}">
                <i class="material-icons">event</i> Timetable
            </a>

            <a class="mdl-navigation__link {{ is_route('dashboard::dj::requests') ? 'is-active' : '' }}"
               href="{{ route('dashboard::dj::requests') }}">
                <i class="material-icons">announcement</i> Request Line
            </a>
        @endif

        @if(auth()->user()->is(App\Models\Group::EVENT))
            <div class="zpan-drawer-separator"></div>

            <span class="mdl-navigation__link" href>Events
            </span>

            <a class="mdl-navigation__link {{ is_route('dashboard::event::timetable') ? 'is-active' : '' }}"
               href="{{ route('dashboard::event::timetable') }}">
                <i class="material-icons">event</i> Timetable
            </a>
        @endif

        @if(auth()->user()->is(App\Models\Group::SENIOR_EVENTS))
            <div class="zpan-drawer-separator"></div>

            <span class="mdl-navigation__link" href>Senior Events</span>

            <a class="mdl-navigation__link {{ is_route('dashboard::senior-events::awaiting-review') ? 'is-active' : '' }}"
               href="{{ route('dashboard::senior-events::awaiting-review') }}">
                <i class="material-icons">event_available</i> Awaiting Review ({{ App\Models\Event::where('week', Carbon\Carbon::now()->weekOfYear)->where('year', Carbon\Carbon::now()->year)->where('approved', false)->count() }})
            </a>
        @endif

        @if(auth()->user()->isManagement())
            <div class="zpan-drawer-separator"></div>

            <span class="mdl-navigation__link" href>Management</span>

            <a class="mdl-navigation__link {{ is_route('dashboard::management::event-type') ? 'is-active' : '' }}"
               href="{{ route('dashboard::management::event-type') }}">
                <i class="material-icons">settings_applications</i> Event Types
            </a>
        @endif

        @if(auth()->user()->isAdmin())
            <div class="zpan-drawer-separator"></div>

            <span class="mdl-navigation__link" href>Administrator</span>

            <a class="mdl-navigation__link {{ is_route('dashboard::admin::connection-info') ? 'is-active' : '' }}"
               href="{{ route('dashboard::admin::connection-info') }}">
                <i class="material-icons">power</i> Update Connection
            </a>

            <a class="mdl-navigation__link {{ is_route('dashboard::admin::request-ban') ? 'is-active' : '' }}"
               href="{{ route('dashboard::admin::request-ban') }}">
                <i class="material-icons">do_not_disturb_alt</i> Request Line Bans
            </a>
        @endif
    </nav>
</div>
