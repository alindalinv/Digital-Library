@php
    $isBookIndex = request()->routeIs('books.index');

    $isFeaturedBooks =
        $isBookIndex &&
        request()->boolean('featured');

    $isNewArrivals =
        $isBookIndex &&
        request()->get('sort') === 'latest' &&
        ! $isFeaturedBooks;

    $isBooksPage =
        request()->routeIs('books.show') ||
        ($isBookIndex && ! $isFeaturedBooks && ! $isNewArrivals);
@endphp

<style>
    /* =========================================================
       Frontend Navigation
       ========================================================= */

    .frontend-nav {
        min-height: 72px;
    }

    .frontend-nav .navbar-container {
        min-height: 72px;
    }

    /* Logo */
    .frontend-nav .navbar-brand {
        flex-shrink: 0;
        min-width: 0;
    }

    .frontend-nav .navbar-brand img {
        max-width: 100%;
    }

    /* Navigation links */
    .frontend-nav .nav-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #5b6573;
        font-weight: 500;
        padding: .55rem .9rem;
        white-space: nowrap;
        transition:
            color .15s ease,
            background-color .15s ease,
            transform .15s ease;
    }

    .frontend-nav .nav-link:hover,
    .frontend-nav .nav-link:focus-visible {
        background-color: #f1f5f9;
        color: var(--primary-color);
    }

    .frontend-nav .nav-link.active {
        background-color: #e8f1ff;
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Primary navigation */
    .frontend-nav .primary-nav {
        gap: .15rem;
    }

    /* User */
    .frontend-nav .user-area {
        flex-shrink: 0;
    }

    .frontend-nav .user-trigger {
        max-width: 220px;

        color: #344054;

        border: 0;
        box-shadow: none !important;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .frontend-nav .user-trigger::after {
        margin-left: .5rem;
    }

    .frontend-nav .user-trigger:hover,
    .frontend-nav .user-trigger:focus-visible {
        background-color: #f1f5f9;
        color: var(--primary-color);
    }

    /* Dropdown */
    .frontend-nav .user-dropdown {
        min-width: 260px;
    }

    .frontend-nav .user-dropdown .user-email {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Auth buttons */
    .frontend-nav .auth-actions {
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    /* Hamburger */
    .frontend-nav .navbar-toggler {
        padding: .4rem .5rem;
        border-radius: .6rem;
        box-shadow: none !important;
    }

    .frontend-nav .navbar-toggler:hover {
        background-color: #f1f5f9;
    }


    /* =========================================================
       Tablet / Mobile
       ========================================================= */

    @media (max-width: 991.98px) {

        .frontend-nav {
            min-height: 64px;
        }

        .frontend-nav .navbar-container {
            min-height: 64px;
        }

        .frontend-nav .navbar-collapse {
            margin-top: .75rem;
            padding: .75rem 0 1rem;

            border-top: 1px solid #e9ecef;
        }

        .frontend-nav .primary-nav {
            gap: .25rem;
        }

        .frontend-nav .primary-nav .nav-item {
            width: 100%;
        }

        .frontend-nav .primary-nav .nav-link {
            width: 100%;
            justify-content: flex-start;

            padding: .7rem .85rem;
        }

        .frontend-nav .user-area {
            width: 100%;
            margin-top: .75rem;
            padding-top: .75rem;

            border-top: 1px solid #e9ecef;
        }

        .frontend-nav .user-area > .dropdown {
            width: 100%;
        }

        .frontend-nav .user-trigger {
            width: 100%;
            justify-content: flex-start;
        }

        .frontend-nav .user-dropdown {
            width: 100%;
            min-width: 0;
            margin-top: .35rem !important;
        }

        .frontend-nav .auth-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
            gap: .25rem;
        }

        .frontend-nav .auth-actions .nav-link {
            width: 100%;
            justify-content: flex-start;
        }
    }


    /* =========================================================
       Small Mobile
       ========================================================= */

    @media (max-width: 575.98px) {

        .frontend-nav .container {
            padding-left: .85rem;
            padding-right: .85rem;
        }

        .frontend-nav .navbar-brand {
            max-width: calc(100% - 55px);
        }

        .frontend-nav .navbar-brand .application-logo {
            max-height: 2rem;
        }

        .frontend-nav .navbar-collapse {
            margin-top: .5rem;
        }

        .frontend-nav .primary-nav .nav-link {
            font-size: .95rem;
        }
    }


    /* =========================================================
       Very Small Phones
       ========================================================= */

    @media (max-width: 359.98px) {

        .frontend-nav .container {
            padding-left: .65rem;
            padding-right: .65rem;
        }

        .frontend-nav .navbar-brand img {
            height: 2rem !important;
        }
    }
</style>


<nav class="frontend-nav navbar navbar-expand-lg navbar-light bg-white border-bottom">

    <div class="container navbar-container">

        {{-- =====================================================
             Logo
             ===================================================== --}}
        <a
            href="{{ route('home') }}"
            class="navbar-brand d-flex align-items-center mb-0"
            aria-label="{{ config('app.name') }} Home"
        >
            <x-frontend.application-logo
                class="d-block application-logo"
                style="height: 2.25rem; width: auto;"
            />

            <span class="ms-2 fs-5 fw-bold text-dark d-none d-md-inline">
                {{ config('app.name') }}
            </span>
        </a>


        {{-- =====================================================
             Mobile / Tablet Toggle
             ===================================================== --}}
        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mobileNav"
            aria-controls="mobileNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        {{-- =====================================================
             Navigation Content
             ===================================================== --}}
        <div
            class="collapse navbar-collapse"
            id="mobileNav"
        >

            {{-- =================================================
                 Primary Navigation
                 ================================================= --}}
            <ul class="navbar-nav primary-nav me-auto mb-2 mb-lg-0">

                {{-- Home --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}"
                    >
                        {{ __('Home') }}
                    </a>
                </li>


                {{-- Books --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ $isBooksPage ? 'active' : '' }}"
                        href="{{ route('books.index') }}"
                    >
                        {{ __('Books') }}
                    </a>
                </li>


                {{-- Featured --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ $isFeaturedBooks ? 'active' : '' }}"
                        href="{{ route('books.index', ['featured' => 1]) }}"
                    >
                        {{ __('Featured') }}
                    </a>
                </li>


                {{-- New Arrivals --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ $isNewArrivals ? 'active' : '' }}"
                        href="{{ route('books.index', ['sort' => 'latest']) }}"
                    >
                        {{ __('New Arrivals') }}
                    </a>
                </li>


                {{-- Authenticated User Links --}}
                @auth

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}"
                        >
                            {{ __('Dashboard') }}
                        </a>
                    </li>


                    {{-- My Borrowings --}}
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}"
                            href="{{ route('borrowings.index') }}"
                        >
                            {{ __('My Borrowings') }}
                        </a>
                    </li>

                @endauth

            </ul>


            {{-- =================================================
                 Right Side
                 ================================================= --}}
            <div class="user-area d-flex align-items-center">

                @auth

                    @php
                        /** @var \App\Models\User $currentUser */
                        $currentUser = Auth::guard('web')->user();
                    @endphp

                    <div class="dropdown">

                        <button
                            class="btn btn-link nav-link user-trigger dropdown-toggle text-decoration-none"
                            type="button"
                            id="userDropdown"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                        >
                            <i class="fas fa-user-circle me-2"></i>

                            <span>
                                {{ $currentUser->name }}
                            </span>
                        </button>


                        <ul
                            class="dropdown-menu dropdown-menu-end shadow-sm border-0 user-dropdown"
                            aria-labelledby="userDropdown"
                        >

                            {{-- User Information --}}
                            <li class="px-3 py-3 border-bottom">

                                <div class="fw-semibold text-dark">
                                    {{ $currentUser->name }}
                                </div>

                                <div class="small text-muted user-email">
                                    {{ $currentUser->email }}
                                </div>

                                @if ($currentUser->roles->isNotEmpty())

                                    <div
                                        class="text-muted mt-1"
                                        style="font-size: .75rem;"
                                    >
                                        Roles:
                                        {{ $currentUser->getRoleNames()->join(', ') }}
                                    </div>

                                @endif

                            </li>


                            {{-- Profile --}}
                            <li>
                                <a
                                    class="dropdown-item"
                                    href="{{ route('profile.edit') }}"
                                >
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    {{ __('Profile') }}
                                </a>
                            </li>


                            {{-- Admin Dashboard --}}
                            @can('access-admin')

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.dashboard') }}"
                                    >
                                        <i class="fas fa-shield-alt me-2 text-muted"></i>
                                        {{ __('Admin Dashboard') }}
                                    </a>
                                </li>

                            @endcan


                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            {{-- Logout --}}
                            <li>
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item"
                                    >
                                        <i class="fas fa-sign-out-alt me-2 text-muted"></i>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </div>

                @else

                    {{-- Guest --}}
                    <div class="auth-actions d-flex flex-wrap gap-2">

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-primary px-3 rounded-5"
                        >
                            {{ __('Log in') }}
                        </a>

                        @if (Route::has('register'))

                            <a
                                href="{{ route('register') }}"
                                class="btn btn-primary px-3 rounded-5"
                            >
                                {{ __('Register') }}
                            </a>

                        @endif

                    </div>

                @endauth

            </div>

        </div>

    </div>

</nav>