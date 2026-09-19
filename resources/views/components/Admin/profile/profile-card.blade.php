<div>
    @php
        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        $displayName = $fullName ?: ($user->name ?? 'User');
        $role = $user->getRoleNames()->first() ?? 'User';
    @endphp

<div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

        <!-- Profile Content -->
        <div class="flex w-full flex-col items-center gap-6 xl:flex-row">

            <!-- Avatar -->
            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-full border border-gray-200">
                <img
                    src="{{ $user->photo
                        ? asset('storage/' . $user->photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=random' }}"
                    alt="{{ $displayName }}"
                    class="h-full w-full object-cover"
                />
            </div>

            <!-- User Information -->
            <div class="order-3 w-full xl:order-2">

                <!-- Name -->
                <h4 class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left">
                    {{ $displayName }}
                </h4>

                <!-- User Meta -->
                <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">

                    <!-- Role -->
                    <p class="text-sm text-gray-500">
                        {{ $role }}
                    </p>

                    @if ($user->job_title)
                        <div class="hidden h-3.5 w-px bg-gray-300 xl:block"></div>

                        <!-- Job Title -->
                        <p class="text-sm text-gray-500">
                            {{ $user->job_title }}
                        </p>
                    @endif

                    @if ($user->organization)
                        <div class="hidden h-3.5 w-px bg-gray-300 xl:block"></div>

                        <!-- Organization -->
                        <p class="text-sm text-gray-500">
                            {{ $user->organization }}
                        </p>
                    @endif

                </div>

                <!-- Email -->
                @if ($user->email)
                    <p class="mt-2 text-center text-sm text-gray-500 xl:text-left">
                        {{ $user->email }}
                    </p>
                @endif

            </div>

            <!-- Social Links -->
            <div class="order-2 flex items-center gap-2 xl:order-3 xl:ml-auto">

                <!-- Facebook -->
                @if ($user->facebook)
                    <a
                        href="{{ $user->facebook }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook"
                        class="shadow-theme-xs flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:text-gray-800"
                    >
                        <svg
                            class="fill-current"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M13.5 21V12.8H16.3L16.7 9.6H13.5V7.55C13.5 6.62 13.76 6 15.1 6H16.8V3.14C16.5 3.1 15.46 3 14.25 3C11.72 3 10 4.54 10 7.36V9.6H7.2V12.8H10V21H13.5Z"
                                fill=""
                            />
                        </svg>
                    </a>
                @endif

                <!-- X / Twitter -->
                @if ($user->twitter)
                    <a
                        href="{{ $user->twitter }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="X"
                        class="shadow-theme-xs flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:text-gray-800"
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M18.244 2H21.5L14.39 10.13L22.75 22H16.2L11.07 14.81L4.77 22H1.51L9.11 13.31L1.09 2H7.8L12.43 8.62L18.244 2ZM17.1 19.92H18.9L6.82 3.97H4.89L17.1 19.92Z"
                                fill="currentColor"
                            />
                        </svg>
                    </a>
                @endif

                <!-- LinkedIn -->
                @if ($user->linkedin)
                    <a
                        href="{{ $user->linkedin }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="LinkedIn"
                        class="shadow-theme-xs flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:text-gray-800"
                    >
                        <svg
                            class="fill-current"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M6.5 8.5H3.25V20H6.5V8.5ZM4.88 3C3.84 3 3 3.84 3 4.88C3 5.92 3.84 6.75 4.88 6.75C5.92 6.75 6.75 5.92 6.75 4.88C6.75 3.84 5.92 3 4.88 3ZM20.75 13.42C20.75 9.95 18.9 8.25 16.42 8.25C14.42 8.25 13.52 9.35 13.02 10.12V8.5H9.75V20H13V14.3C13 12.8 13.28 11.35 15.15 11.35C16.99 11.35 17 13.07 17 14.4V20H20.25L20.75 13.42Z"
                                fill=""
                            />
                        </svg>
                    </a>
                @endif

                <!-- Instagram -->
                @if ($user->instagram)
                    <a
                        href="{{ $user->instagram }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram"
                        class="shadow-theme-xs flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:text-gray-800"
                    >
                        <svg
                            class="fill-current"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M7.6 2.5H16.4C19.22 2.5 21.5 4.78 21.5 7.6V16.4C21.5 19.22 19.22 21.5 16.4 21.5H7.6C4.78 21.5 2.5 19.22 2.5 16.4V7.6C2.5 4.78 4.78 2.5 7.6 2.5ZM16.4 4.5H7.6C5.88 4.5 4.5 5.88 4.5 7.6V16.4C4.5 18.12 5.88 19.5 7.6 19.5H16.4C18.12 19.5 19.5 18.12 19.5 16.4V7.6C19.5 5.88 18.12 4.5 16.4 4.5ZM12 7.5C14.49 7.5 16.5 9.51 16.5 12C16.5 14.49 14.49 16.5 12 16.5C9.51 16.5 7.5 14.49 7.5 12C7.5 9.51 9.51 7.5 12 7.5ZM12 9.5C10.62 9.5 9.5 10.62 9.5 12C9.5 13.38 10.62 14.5 12 14.5C13.38 14.5 14.5 13.38 14.5 12C14.5 10.62 13.38 9.5 12 9.5ZM17 6.5C17.55 6.5 18 6.95 18 7.5C18 8.05 17.55 8.5 17 8.5C16.45 8.5 16 8.05 16 7.5C16 6.95 16.45 6.5 17 6.5Z"
                                fill=""
                            />
                        </svg>
                    </a>
                @endif

            </div>
        </div>

        <!-- Edit Button -->
        <button
            @click="$dispatch('open-profile-info-modal')"
            type="button"
            class="shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-gray-800 lg:inline-flex lg:w-auto"
        >
            <svg
                class="fill-current"
                width="18"
                height="18"
                viewBox="0 0 18 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M15.0911 2.78277C15.4826 3.17429 15.4826 3.80946 15.0911 4.20098L6.20102 13.0911L3.5 13.5L3.90891 10.799L12.799 1.90891C13.1905 1.51739 13.8257 1.51739 14.2172 1.90891L15.0911 2.78277ZM2.12708 14.8729L2.75592 10.7435L12.0911 1.40891C12.8721 0.62786 14.1439 0.62786 14.9249 1.40891L15.799 2.28277C16.58 3.06382 16.58 4.33564 15.799 5.1167L6.46433 14.4514L2.33496 15.0802L2.12708 14.8729Z"
                    fill=""
                />
            </svg>

            Edit
        </button>

    </div>
</div>

</div>
