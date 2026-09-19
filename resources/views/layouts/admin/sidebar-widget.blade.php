<div class="mx-auto mb-10 w-full max-w-[15rem] rounded-2xl
            bg-gray-50 px-4 py-5 text-center
            border border-gray-200
            dark:bg-white/[0.03] dark:border-gray-800">

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit"
            class="flex w-full items-center justify-center gap-2 p-3
                   font-medium text-white rounded-lg
                   bg-brand-500 hover:bg-brand-600
                   focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-2
                   dark:focus:ring-offset-gray-900
                   transition-colors duration-200
                   text-theme-sm">
            {{-- Logout icon --}}
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                 fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M3.5 4.5C3.5 3.39543 4.39543 2.5 5.5 2.5H9.5C10.0523 2.5 10.5 2.94772 10.5 3.5C10.5 4.05228 10.0523 4.5 9.5 4.5H5.5V15.5H9.5C10.0523 15.5 10.5 15.9477 10.5 16.5C10.5 17.0523 10.0523 17.5 9.5 17.5H5.5C4.39543 17.5 3.5 16.6046 3.5 15.5V4.5ZM12.7929 6.79289C13.1834 6.40237 13.8166 6.40237 14.2071 6.79289L16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L14.2071 13.2071C13.8166 13.5976 13.1834 13.5976 12.7929 13.2071C12.4024 12.8166 12.4024 12.1834 12.7929 11.7929L13.5858 11H8.5C7.94772 11 7.5 10.5523 7.5 10C7.5 9.44772 7.94772 9 8.5 9H13.5858L12.7929 8.20711C12.4024 7.81658 12.4024 7.18342 12.7929 6.79289Z"
                    fill="currentColor" />
            </svg>
            <span>{{ __('Sign Out') }}</span>
        </button>
    </form>
</div>