<section>
    {{-- ============ Header ============ --}}
    <header class="mb-4">
        <h3 class="h5 fw-bold text-dark mb-1">
            <i class="fas fa-share-alt text-primary me-2"></i> Social Links
        </h3>
        <p class="text-secondary small mb-0">
            Add links to your social profiles. Leave blank to hide.
        </p>
    </header>

    {{-- ============ Form ============ --}}
    <form method="POST"
          action="{{ route('profile.social.update') }}"
          class="mt-4">
        @csrf
        @method('PATCH')

        @php
            $platforms = [
                'facebook'  => ['label' => 'Facebook',  'icon' => 'fa-facebook-f',  'color' => '#1877F2', 'placeholder' => 'https://facebook.com/username'],
                'twitter'   => ['label' => 'Twitter/X', 'icon' => 'fa-twitter',     'color' => '#1DA1F2', 'placeholder' => 'https://twitter.com/username'],
                'linkedin'  => ['label' => 'LinkedIn',  'icon' => 'fa-linkedin-in', 'color' => '#0A66C2', 'placeholder' => 'https://linkedin.com/in/username'],
                'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram',   'color' => '#E4405F', 'placeholder' => 'https://instagram.com/username'],
            ];
        @endphp

        <div class="row g-3">
            @foreach($platforms as $field => $meta)
                <div class="col-12 col-md-6">
                    <label for="{{ $field }}" class="form-label fw-semibold">
                        <i class="fab {{ $meta['icon'] }} me-2"
                           style="color: {{ $meta['color'] }};"></i>
                        {{ $meta['label'] }}
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fab {{ $meta['icon'] }}"
                               style="color: {{ $meta['color'] }};"></i>
                        </span>

                        <input type="url"
                               name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control border-start-0 @error($field) is-invalid @enderror"
                               value="{{ old($field, $user->{$field}) }}"
                               placeholder="{{ $meta['placeholder'] }}"
                               autocomplete="off">

                        @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Live preview link --}}
                    @if($user->{$field})
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-link me-1"></i>
                            <a href="{{ $user->{$field} }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="text-decoration-none">
                                {{ Str::limit($user->{$field}, 40) }}
                            </a>
                        </small>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ============ Preview Card ============ --}}
        @if($user->hasSocialLinks())
            <div class="mt-4 p-3 bg-light rounded-3">
                <p class="small fw-semibold text-dark mb-2">
                    <i class="fas fa-eye me-1"></i> Preview on your profile:
                </p>

                <div class="d-flex flex-wrap gap-3">
                    @foreach($user->socialLinks() as $platform => $url)
                        <a href="{{ $url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-decoration-none d-flex align-items-center gap-2"
                           style="color: {{ $platforms[$platform]['color'] ?? '#6c757d' }};">
                            <i class="fab {{ $platforms[$platform]['icon'] ?? 'fa-link' }} fs-5"></i>
                            <span class="small fw-semibold">
                                {{ $platforms[$platform]['label'] ?? ucfirst($platform) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============ Submit ============ --}}
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary fw-semibold">
                <i class="fas fa-save me-1"></i> Save Social Links
            </button>

            @if(session('status') === 'social-updated')
                <span class="text-success small">
                    <i class="fas fa-check-circle me-1"></i> Saved.
                </span>
            @endif
        </div>
    </form>
</section>