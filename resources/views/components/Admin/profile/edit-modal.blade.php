@props(['user'])

<div x-show="openProfileInfoModal" x-cloak
    class="fixed inset-0 z-99999 flex items-center justify-center bg-gray-900/50 p-4">

    <form method="POST" action="{{ route('admin.profile.update') }}"
        class="flex w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-xl" style="max-height: 90vh;">
        @csrf
        @method('PATCH')

        {{-- ✅ Header — fixed, never scrolls --}}
        <div class="flex shrink-0 items-center justify-between border-b border-gray-200 p-5">
            <h3 class="text-lg font-semibold text-gray-800">Edit Profile</h3>
            <button type="button" @click="openProfileInfoModal = false" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        {{-- ✅ Body — only this scrolls --}}
        <div class="flex-1 overflow-y-auto p-5">
                    <div class="max-h-[70vh] overflow-y-auto p-5">
                        <div class="grid grid-cols-1 gap-5">

                            {{-- Email --}}
                            <div>
                                <label for="email"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Phone
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">
                                @error('phone')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label for="gender"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Gender
                                </label>
                                <select id="gender" name="gender"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500">
                                    <option value="" class="dark:bg-gray-900">— Select —</option>
                                    <option value="male" @selected(old('gender', $user->gender) === 'male')
                                        class="dark:bg-gray-900">Male</option>
                                    <option value="female" @selected(old('gender', $user->gender) === 'female')
                                        class="dark:bg-gray-900">Female</option>
                                    <option value="other" @selected(old('gender', $user->gender) === 'other')
                                        class="dark:bg-gray-900">Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="date_of_birth"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date of Birth
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:[color-scheme:dark] dark:focus:border-blue-500">
                                @error('date_of_birth')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label for="address"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Address
                                </label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full resize-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Bio --}}
                            <div>
                                <label for="bio"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Bio
                                </label>
                                <textarea id="bio" name="bio" rows="3" placeholder="Tell us a bit about yourself..."
                                    class="w-full resize-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
        </div>
        <div class="flex shrink-0 items-center justify-end gap-3 border-t border-gray-200 p-5">
            <button type="button" @click="openProfileInfoModal = false" class="rounded-full border px-4 py-2 text-sm">
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
