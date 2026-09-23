@php
    $isBookIndex = request()->routeIs('books.index');
    $isFeaturedBooks = $isBookIndex && request()->boolean('featured');
    $isNewArrivals = $isBookIndex && request()->has('sort') && request()->get('sort') === 'latest' && ! $isFeaturedBooks;
    $isBooksPage = request()->routeIs('books.show') || ($isBookIndex && ! $isFeaturedBooks && ! $isNewArrivals);
@endphp

<style>
    .frontend-nav .nav-link { border-radius: .75rem; color: #5b6573; font-weight: 500; padding: .5rem 1rem; transition: color .15s ease, background-color .15s ease;margin-right: 0.5rem; }
    .frontend-nav .nav-link:hover, .frontend-nav .nav-link:focus-visible { background: #f1f5f9; color: var(--primary-color); }
    .frontend-nav .nav-link.active { background: #e8f1ff; color: var(--primary-color); font-weight: 600; }
    .frontend-nav .user-trigger { color: #344054; }
    @media (max-width: 575.98px) {
        .frontend-nav .navbar-nav { padding-top: .75rem; }
        .frontend-nav .nav-link { margin-bottom: .25rem; }
        .frontend-nav .auth-actions { border-top: 1px solid #e9ecef; margin-top: .75rem; padding-top: .75rem; }
    }
</style>

<nav class="frontend-nav navbar navbar-expand-sm navbar-light bg-white border-bottom">
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

                <li class="nav-item">
                    <a class="nav-link {{ $isBooksPage ? 'active' : '' }}"
                       href="{{ route('books.index') }}">
                        {{ __('Books') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $isFeaturedBooks ? 'active' : '' }}"
                       href="{{ route('books.index', ['featured' => 1]) }}">
                        {{ __('Featured') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $isNewArrivals ? 'active' : '' }}"
                       href="{{ route('books.index', ['sort' => 'latest']) }}">
                        {{ __('New Arrivals') }}
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}"
                           href="{{ route('borrowings.index') }}">
                            {{ __('My Borrowings') }}
                        </a>
                    </li>
                @endauth
            </ul>

            {{-- Right side: user dropdown or login/register --}}
            <div class="d-flex align-items-center">
                @auth
                    @php
                        /** @var \App\Models\User $currentUser */
                        $currentUser = Auth::user();
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-link nav-link user-trigger dropdown-toggle text-decoration-none d-inline-flex align-items-center"
                                type="button"
                                id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <span>{{ $currentUser->name }}</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            {{-- User info --}}
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-medium text-dark">{{ $currentUser->name }}</div>
                                <div class="small text-muted">{{ $currentUser->email }}</div>
                                @if($currentUser->roles)
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        Roles: {{ $currentUser->getRoleNames()->join(', ') }}
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
                    <div class="auth-actions d-flex gap-3">
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