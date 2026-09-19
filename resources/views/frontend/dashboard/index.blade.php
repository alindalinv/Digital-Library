<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="bg-white shadow-sm rounded-3 overflow-hidden">
                <div class="p-4 text-dark">
                    <h3 class="fs-3 fw-bold mb-4">Welcome back, {{ auth()->user()->name }}! 👋</h3>

                    <p class="mb-4">You're logged in to the Digital Library system.</p>

                    @if(auth()->user()->roles)
                        <div class="alert alert-primary border-start border-4 border-primary d-flex align-items-start mb-4" role="alert">
                            <div class="flex-shrink-0 me-3">
                                <svg style="width: 1.25rem; height: 1.25rem;" class="text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="small mb-0 text-primary-emphasis">
                                    <strong>Roles:</strong> {{ auth()->user()->getRoleNames()->join(', ') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- <div class="row g-3 mt-4">
                        <div class="col-12 col-md-4">
                            <div class="bg-success-subtle p-3 rounded-3">
                                <h4 class="fw-semibold text-success-emphasis">Books Borrowed</h4>
                                <p class="fs-3 fw-bold text-success-emphasis mb-0">{{ auth()->user()->borrowings()->whereNull('returned_at')->count() }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="bg-primary-subtle p-3 rounded-3">
                                <h4 class="fw-semibold text-primary-emphasis">Total Books Read</h4>
                                <p class="fs-3 fw-bold text-primary-emphasis mb-0">{{ auth()->user()->borrowings()->whereNotNull('returned_at')->count() }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="bg-secondary-subtle p-3 rounded-3">
                                <h4 class="fw-semibold text-secondary-emphasis">Member Since</h4>
                                <p class="fs-4 fw-bold text-secondary-emphasis mb-0">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>