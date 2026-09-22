@props(['user'])

<div x-show="openProfileHeaderModal" x-cloak x-transition.opacity
    class="fixed inset-0 z-99999 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-gray-900/70"
    @click.self="openProfileHeaderModal = false" @keydown.escape.window="openProfileHeaderModal = false">

    <div x-show="openProfileHeaderModal" x-transition
        class="flex w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-xl dark:border dark:border-gray-800 dark:bg-gray-900"
        style="max-height: 90vh;">

        {{-- ============ Header ============ --}}
        <div class="flex shrink-0 items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Edit Profile Header
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Update your photo, name, and social links
                    </p>
                </div>
            </div>

            <button type="button" @click="openProfileHeaderModal = false" aria-label="Close modal"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- ============ Form ============ --}}
        <form method="POST" action="{{ route('admin.profile.header.update') }}" enctype="multipart/form-data"
            class="flex flex-1 flex-col overflow-hidden">
            @csrf
            @method('PATCH')

            {{-- ============ Body (scrollable) ============ --}}
            <div class="flex-1 overflow-y-auto p-5">
                <div class="grid grid-cols-1 gap-5">

                    {{-- Photo --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Profile Photo
                        </label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->displayName() }}"
                                id="avatarPreviewHeader"
                                class="h-16 w-16 shrink-0 rounded-full border border-gray-200 object-cover dark:border-gray-700">
                            <input type="file" name="photo" accept="image/*"
                                onchange="document.getElementById('avatarPreviewHeader').src = window.URL.createObjectURL(event.target.files[0])"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-3 file:rounded-full file:border-0
                                          file:bg-blue-50 file:px-4 file:py-2
                                          file:text-sm file:font-medium file:text-blue-600
                                          hover:file:bg-blue-100
                                          dark:file:bg-blue-500/15 dark:file:text-blue-400
                                          dark:hover:file:bg-blue-500/25">
                        </div>
                        @error('photo')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- First Name --}}
                    <div>
                        <label for="first_name_header"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            First Name
                        </label>
                        <input type="text" id="first_name_header" name="first_name"
                            value="{{ old('first_name', $user->first_name) }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:focus:border-blue-500">
                        @error('first_name')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label for="last_name_header"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Last Name
                        </label>
                        <input type="text" id="last_name_header" name="last_name"
                            value="{{ old('last_name', $user->last_name) }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:focus:border-blue-500">
                        @error('last_name')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Job Title --}}
                    <div>
                        <label for="job_title_header"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Job Title
                        </label>
                        <input type="text" id="job_title_header" name="job_title"
                            value="{{ old('job_title', $user->job_title) }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:focus:border-blue-500">
                        @error('job_title')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Organization --}}
                    <div>
                        <label for="organization_header"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Organization
                        </label>
                        <input type="text" id="organization_header" name="organization"
                            value="{{ old('organization', $user->organization) }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:focus:border-blue-500">
                        @error('organization')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ============ Social Links ============ --}}
                    <div class="border-t border-gray-200 pt-5 dark:border-gray-800">
                        <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Social Links
                        </h4>

                        @php
                            $platforms = [
                                'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook-f', 'color' => '#1877F2', 'placeholder' => 'https://facebook.com/username'],
                                'twitter' => ['label' => 'Twitter/X', 'icon' => 'fa-twitter', 'color' => '#1DA1F2', 'placeholder' => 'https://twitter.com/username'],
                                'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fa-linkedin-in', 'color' => '#0A66C2', 'placeholder' => 'https://linkedin.com/in/username'],
                                'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram', 'color' => '#E4405F', 'placeholder' => 'https://instagram.com/username'],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 gap-4">
                            @foreach($platforms as $field => $meta)
                                <div>
                                    <label for="{{ $field }}_header"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fab {{ $meta['icon'] }} me-1" style="color: {{ $meta['color'] }};"></i>
                                        {{ $meta['label'] }}
                                    </label>
                                    <input type="url" id="{{ $field }}_header" name="{{ $field }}"
                                        value="{{ old($field, $user->{$field}) }}" placeholder="{{ $meta['placeholder'] }}"
                                        class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">
                                    @error($field)
                                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- ============ Footer ============ --}}
            <div
                class="flex shrink-0 items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50">
                <button type="button" @click="openProfileHeaderModal = false"
                    class="rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" class="p-3
                   font-medium text-white rounded-lg
                   bg-brand-500 hover:bg-brand-600
                   focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-2
                   dark:focus:ring-offset-gray-900
                   transition-colors duration-200
                   text-theme-sm">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>
