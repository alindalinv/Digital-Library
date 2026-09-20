<section>
    <header class="mb-4">
        <h3 class="h5 fw-bold text-dark mb-1">
            <i class="fas fa-user text-primary me-2"></i> Profile Information
        </h3>
        <p class="text-secondary small mb-0">
            Update your personal information and profile photo.
        </p>
    </header>

    <form method="POST"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="mt-4">
        @csrf
        @method('PATCH')

        {{-- ============ Photo Upload ============ --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Profile Photo</label>

            <div class="d-flex align-items-center gap-3">
                <img src="{{ $user->avatarUrl() }}"
                     alt="{{ $user->displayName() }}"
                     id="avatarPreview"
                     width="80" height="80"
                     class="rounded-circle border object-fit-cover">

                <div class="flex-grow-1">
                    <input type="file"
                           name="photo"
                           id="photo"
                           class="form-control @error('photo') is-invalid @enderror"
                           accept="image/*"
                           onchange="previewAvatar(event)">

                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        JPG, PNG or WEBP. Max 2MB.
                    </small>
                </div>
            </div>
        </div>

        {{-- ============ Name ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label fw-semibold">First Name</label>
                <input type="text"
                       name="first_name"
                       id="first_name"
                       class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name', $user->first_name) }}">
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="last_name" class="form-label fw-semibold">Last Name</label>
                <input type="text"
                       name="last_name"
                       id="last_name"
                       class="form-control @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name', $user->last_name) }}">
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ============ Email ============ --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning small mt-2 mb-0">
                    Your email is unverified.
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                        Resend verification email
                    </button>
                </div>
            @endif
        </div>

        {{-- ============ Phone / Gender / DOB ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="phone" class="form-label fw-semibold">Phone</label>
                <input type="text"
                       name="phone"
                       id="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="gender" class="form-label fw-semibold">Gender</label>
                <select name="gender"
                        id="gender"
                        class="form-select @error('gender') is-invalid @enderror">
                    <option value="">— Select —</option>
                    <option value="male"   @selected(old('gender', $user->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                    <option value="other"  @selected(old('gender', $user->gender) === 'other')>Other</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="date_of_birth" class="form-label fw-semibold">Date of Birth</label>
                <input type="date"
                       name="date_of_birth"
                       id="date_of_birth"
                       class="form-control @error('date_of_birth') is-invalid @enderror"
                       value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ============ Organization / Job ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="organization" class="form-label fw-semibold">Organization</label>
                <input type="text"
                       name="organization"
                       id="organization"
                       class="form-control @error('organization') is-invalid @enderror"
                       value="{{ old('organization', $user->organization) }}">
                @error('organization')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="job_title" class="form-label fw-semibold">Job Title</label>
                <input type="text"
                       name="job_title"
                       id="job_title"
                       class="form-control @error('job_title') is-invalid @enderror"
                       value="{{ old('job_title', $user->job_title) }}">
                @error('job_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ============ Bio ============ --}}
        <div class="mb-3">
            <label for="bio" class="form-label fw-semibold">Bio</label>
            <textarea name="bio"
                      id="bio"
                      rows="4"
                      class="form-control @error('bio') is-invalid @enderror"
                      placeholder="Tell us a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ============ Address ============ --}}
        <div class="mb-4">
            <label for="address" class="form-label fw-semibold">Address</label>
            <textarea name="address"
                      id="address"
                      rows="2"
                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ============ Submit ============ --}}
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary fw-semibold">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>

            @if(session('status') === 'profile-updated')
                <span class="text-success small">
                    <i class="fas fa-check-circle me-1"></i> Saved.
                </span>
            @endif
        </div>
    </form>
</section>

@push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush