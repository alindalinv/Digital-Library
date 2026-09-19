<section>
    <header>
        <h2 class="fs-5 fw-medium text-dark">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 small text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Keep data-bs-toggle as a FALLBACK if JS fails --}}
    <button type="button"
            id="open-delete-modal"
            class="btn btn-danger mt-4"
            data-bs-toggle="modal"
            data-bs-target="#confirm-user-deletion">
        {{ __('Delete Account') }}
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1"
         aria-labelledby="confirm-user-deletion-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirm-user-deletion-title">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h5>
                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="{{ __('Close') }}"></button>
                    </div>

                    <div class="modal-body">
                        <p class="small text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-3">
                            <label for="delete-password" class="visually-hidden">
                                {{ __('Password') }}
                            </label>

                            <input type="password"
                                   id="delete-password"
                                   name="password"
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                   placeholder="{{ __('Password') }}"
                                   autocomplete="current-password"
                                   required>

                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-light border"
                                data-bs-dismiss="modal"
                                id="cancel-delete-button">
                            {{ __('Cancel') }}
                        </button>

                        <button type="submit" id="confirm-delete-button" class="btn btn-danger">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    (function () {
        function init() {
            // Wait until Bootstrap is available
            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                return false;
            }

            const modalElement  = document.getElementById('confirm-user-deletion');
            const openButton    = document.getElementById('open-delete-modal');
            const form          = document.getElementById('delete-account-form');
            const deleteButton  = document.getElementById('confirm-delete-button');
            const cancelButton  = document.getElementById('cancel-delete-button');
            const passwordInput = document.getElementById('delete-password');

            if (!modalElement || !openButton || !form || !deleteButton) return true;

            // Avoid double-init if Livewire/Inertia re-renders
            if (modalElement.dataset.deleteInit === '1') return true;
            modalElement.dataset.deleteInit = '1';

            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            let isSubmitting = false;

            // ── Open modal (JS controlled) ───────────────────────────
            openButton.addEventListener('click', function (e) {
                if (isSubmitting) return;
                if (modalElement.classList.contains('show')) {
                    e.preventDefault();
                    return;
                }
                // Let Bootstrap's data API open it (fallback safe),
                // but also ensure our instance is the one used.
                e.preventDefault();
                modal.show();
            });

            // ── Focus password on open ───────────────────────────────
            modalElement.addEventListener('shown.bs.modal', function () {
                passwordInput?.focus();
            });

            // ── Submit: lock UI, show spinner ────────────────────────
            form.addEventListener('submit', function (e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                isSubmitting = true;

                deleteButton.disabled = true;
                cancelButton.disabled = true;
                passwordInput.disabled = true;

                deleteButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"
                          role="status" aria-hidden="true"></span>
                    {{ __('Deleting…') }}
                `;

                const body = form.querySelector('.modal-body');
                if (body) {
                    body.innerHTML = `
                        <div class="text-center py-3">
                            <div class="spinner-border text-danger mb-3" role="status"></div>
                            <p class="mb-0 text-muted small">
                                {{ __('Your account is being deleted. You will be redirected shortly…') }}
                            </p>
                        </div>
                    `;
                }
            });

            // ── Reset UI on user-close (not on submit) ───────────────
            modalElement.addEventListener('hidden.bs.modal', function () {
                if (isSubmitting) return;

                deleteButton.disabled = false;
                cancelButton.disabled = false;
                passwordInput.disabled = false;
                deleteButton.innerHTML = `{{ __('Delete Account') }}`;
                if (passwordInput) passwordInput.value = '';

                sessionStorage.removeItem('delete-modal-shown');
            });

            // ── Re-open once on validation error ─────────────────────
            @if ($errors->userDeletion->isNotEmpty())
                if (!sessionStorage.getItem('delete-modal-shown')) {
                    modal.show();
                    sessionStorage.setItem('delete-modal-shown', '1');
                }
            @endif

            return true;
        }

        // Try immediately, then poll until Bootstrap is ready
        if (!init()) {
            const t = setInterval(function () {
                if (init()) clearInterval(t);
            }, 50);
            // Give up after 5s
            setTimeout(() => clearInterval(t), 5000);
        }

        // Re-init after Livewire / Inertia navigations
        document.addEventListener('livewire:navigated', init);
        document.addEventListener('inertia:finish', init);
    })();
    </script>
</section>