<nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <div class="container">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center mb-0">
            <x-frontend.application-logo class="d-block" style="height: 2.25rem; width: auto;" />
            <span class="ms-2 fs-5 fw-bold text-dark d-none d-sm-inline">
                {{ config('app.name') }}
            </span>
        </a>

        {{-- Hamburger (Bootstrap collapse toggle) --}}
        <button class="navbar-toggler d-sm-none border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mobileNav"
                aria-controls="mobileNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Collapsible content --}}
        <div class="collapse navbar-collapse" id="mobileNav">

            {{-- Primary nav links --}}
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">
                        {{ __('Home') }}
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}"
                       href="{{ route('books.index') }}">
                        {{ __('Books') }}
                    </a>
                </li> --}}

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                @endauth
            </ul>

            {{-- Right side: user dropdown or login/register --}}
            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle text-decoration-none d-inline-flex align-items-center"
                                type="button"
                                id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <span>{{ Auth::user()->name }}</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            {{-- User info --}}
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-medium text-dark">{{ Auth::user()->name }}</div>
                                <div class="small text-muted">{{ Auth::user()->email }}</div>
                                @if(Auth::user()->roles)
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        Roles: {{ Auth::user()->getRoleNames()->join(', ') }}
                                    </div>
                                @endif
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    {{ __('Profile') }}
                                </a>
                            </li>

                            @can('access-admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        {{ __('Admin Dashboard') }}
                                    </a>
                                </li>
                            @endcan

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="nav-link">
                            {{ __('Log in') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>