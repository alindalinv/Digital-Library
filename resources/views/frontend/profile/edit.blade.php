<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Profile') }}
            </h2>
            <span class="text-muted small">
                Manage your account settings
            </span>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">

            {{-- ============ Profile Header Card ============ --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 p-md-5 bg-primary text-white">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-4">

                        <div class="position-relative">
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->displayName() }}" width="96" height="96"
                                class="rounded-circle border border-4 border-white shadow-sm object-fit-cover">
                        </div>

                        <div class="text-center text-md-start flex-grow-1">
                            <h3 class="fw-bold mb-1">{{ $user->displayName() }}</h3>
                            <p class="text-white-50 mb-2">
                                <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                            </p>

                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                                @if($user->job_title)
                                    <span class="badge bg-white text-primary">
                                        <i class="fas fa-briefcase me-1"></i> {{ $user->job_title }}
                                    </span>
                                @endif

                                @if($user->organization)
                                    <span class="badge bg-white text-primary">
                                        <i class="fas fa-building me-1"></i> {{ $user->organization }}
                                    </span>
                                @endif

                                @if($user->getRoleNames()->isNotEmpty())
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-user-shield me-1"></i>
                                        {{ $user->getRoleNames()->join(', ') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-center text-md-end">
                            <p class="text-white-50 small mb-1">Member since</p>
                            <p class="fw-semibold mb-0">
                                {{ $user->created_at->format('M Y') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ============ Flash Messages ============ --}}
            @php
                $flashMessages = [
                    'profile-updated'  => ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Profile updated successfully.'],
                    'social-updated'   => ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Social links updated successfully.'],
                    'password-updated' => ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Password updated successfully.'],
                    'account-deleted'  => ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Your account has been deleted.'],
                ];
            @endphp

            @if(session('status') && isset($flashMessages[session('status')]))
                @php $flash = $flashMessages[session('status')]; @endphp
                <div class="alert alert-{{ $flash['type'] }} alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas {{ $flash['icon'] }} me-2"></i>
                    {{ $flash['text'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Also support generic `success` flash key --}}
            @if(session('success') && ! session('status'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ============ Tabs Card ============ --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <ul class="nav nav-tabs nav-fill border-0" id="profileTabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab"
                                data-bs-target="#info-pane" type="button" role="tab">
                                <i class="fas fa-user me-2"></i> Profile Info
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="social-tab" data-bs-toggle="tab"
                                data-bs-target="#social-pane" type="button" role="tab">
                                <i class="fas fa-share-alt me-2"></i> Social Links
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="password-tab" data-bs-toggle="tab"
                                data-bs-target="#password-pane" type="button" role="tab">
                                <i class="fas fa-lock me-2"></i> Password
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold text-danger" id="danger-tab" data-bs-toggle="tab"
                                data-bs-target="#danger-pane" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
                            </button>
                        </li>

                    </ul>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="tab-content" id="profileTabsContent">

                        {{-- ========== Tab 1: Profile Info ========== --}}
                        <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
                            @include('frontend.profile.partials.update-profile-information-form')
                        </div>

                        {{-- ========== Tab 2: Social Links ========== --}}
                        <div class="tab-pane fade" id="social-pane" role="tabpanel">
                            @include('frontend.profile.partials.update-social-links-form')
                        </div>

                        {{-- ========== Tab 3: Password ========== --}}
                        <div class="tab-pane fade" id="password-pane" role="tabpanel">
                            @include('frontend.profile.partials.update-password-form')
                        </div>

                        {{-- ========== Tab 4: Danger Zone ========== --}}
                        <div class="tab-pane fade" id="danger-pane" role="tabpanel">
                            @include('frontend.profile.partials.delete-user-form')
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <style>
            .object-fit-cover {
                object-fit: cover;
            }

            #profileTabs .nav-link {
                color: #6c757d;
                border: none;
                border-bottom: 3px solid transparent;
                padding: 1rem 1rem;
                transition: all .15s ease;
            }

            #profileTabs .nav-link:hover {
                color: #0d6efd;
                border-bottom-color: #e9ecef;
            }

            #profileTabs .nav-link.active {
                color: #0d6efd;
                background: transparent;
                border-bottom-color: #0d6efd;
            }

            #profileTabs .nav-link.text-danger.active {
                color: #dc3545 !important;
                border-bottom-color: #dc3545;
            }
        </style>
    @endpush
</x-app-layout>