@php
    $user = auth()->user();
    $avatarUrl = $user->avatarUrl();

    $menuItems = [
        [
            'text'  => __('Edit Profile'),
            'route' => 'admin.profile.edit',
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                            fill="currentColor" />
                    </svg>',
        ],
    ];
@endphp

<div class="relative" x-data="{
        dropdownOpen: false,
        toggleDropdown() { this.dropdownOpen = !this.dropdownOpen; },
        closeDropdown() { this.dropdownOpen = false; }
     }" @click.away="closeDropdown()" @keydown.escape.window="closeDropdown()">

    {{-- ── Trigger button ─────────────────────────────────────────── --}}
    <button type="button" @click.prevent="toggleDropdown()" :aria-expanded="dropdownOpen.toString()"
        aria-haspopup="menu" class="flex items-center rounded-lg px-1 py-1
               text-gray-700 hover:bg-gray-100
               dark:text-gray-300 dark:hover:bg-gray-800
               focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/50
               transition-colors duration-150">

        <span class="mr-3 overflow-hidden rounded-full h-11 w-11 ring-1 ring-gray-200 dark:ring-gray-700">
            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
        </span>

        <span class="hidden sm:block mr-1 font-medium text-theme-sm truncate max-w-[120px]">
            {{ $user->name }}
        </span>

        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    {{-- ── Dropdown panel ─────────────────────────────────────────── --}}
    <div x-cloak x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" role="menu" class="absolute end-0 mt-3 flex w-[260px] max-w-[calc(100vw-2rem)] flex-col
                rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-[99999]
                dark:border-gray-800 dark:bg-gray-900
                transition-colors duration-200">

        {{-- User info --}}
        <div class="px-1">
            <span class="block font-medium text-gray-700 text-theme-sm truncate
                         dark:text-gray-200">
                {{ $user->name }}
            </span>

            <span class="mt-0.5 block text-theme-xs text-gray-500 truncate
                         dark:text-gray-400">
                {{ $user->email }}
            </span>
        </div>

        {{-- Menu items --}}
        <ul class="flex flex-col gap-1 pt-4 pb-3" role="none">
            @foreach ($menuItems as $item)
                <li role="none">
                    <a href="{{ route($item['route']) }}" role="menuitem" class="group flex items-center gap-3 px-3 py-2 font-medium rounded-lg text-theme-sm
                                      text-gray-700 hover:bg-gray-100 hover:text-gray-900
                                      dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white
                                      transition-colors duration-150">

                        <span class="text-gray-500 group-hover:text-gray-700
                                             dark:text-gray-400 dark:group-hover:text-white
                                             transition-colors duration-150">
                            {!! $item['icon'] !!}
                        </span>

                        {{ $item['text'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Sign out --}}
        <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf

                <button type="submit" class="group flex w-full items-center gap-3 rounded-lg px-3 py-2
                           text-theme-sm font-medium
                           text-gray-700 hover:bg-gray-100 hover:text-gray-900
                           dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/50
                           transition-colors duration-150">

                    <svg class="shrink-0 transition-colors duration-150
                                text-gray-500 group-hover:text-gray-700
                                dark:text-gray-400 dark:group-hover:text-white" width="20" height="20"
                        viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.5 4.5C3.5 3.39543 4.39543 2.5 5.5 2.5H9.5C10.0523 2.5 10.5 2.94772 10.5 3.5C10.5 4.05228 10.0523 4.5 9.5 4.5H5.5V15.5H9.5C10.0523 15.5 10.5 15.9477 10.5 16.5C10.5 17.0523 10.0523 17.5 9.5 17.5H5.5C4.39543 17.5 3.5 16.6046 3.5 15.5V4.5ZM12.7929 6.79289C13.1834 6.40237 13.8166 6.40237 14.2071 6.79289L16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L14.2071 13.2071C13.8166 13.5976 13.1834 13.5976 12.7929 13.2071C12.4024 12.8166 12.4024 12.1834 12.7929 11.7929L13.5858 11H8.5C7.94772 11 7.5 10.5523 7.5 10C7.5 9.44772 7.94772 9 8.5 9H13.5858L12.7929 8.20711C12.4024 7.81658 12.4024 7.18342 12.7929 6.79289Z"
                            fill="currentColor" />
                    </svg>

                    <span>{{ __('Sign Out') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>