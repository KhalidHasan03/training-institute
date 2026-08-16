<div>
    <div>
        <h1 class="font-display text-2xl font-bold text-slate-900">Profile</h1>
        <p class="mt-1 text-sm text-slate-500">Manage your personal information and account security</p>
    </div>

    @if (session()->has('trainer-profile-saved'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('trainer-profile-saved') }}</p>
        </div>
    @endif

    @if (session()->has('trainer-password-saved'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('trainer-password-saved') }}</p>
        </div>
    @endif

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <form wire:submit="save" class="card p-6">
                <h3 class="font-display text-base font-bold text-slate-900">Profile Information</h3>
                <p class="mt-1 text-sm text-slate-500">Update your personal details.</p>

                <div class="mt-6 flex items-center gap-4">
                    <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-brand-100">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Photo preview" class="h-full w-full object-cover">
                        @elseif ($this->trainer?->photo)
                            <img src="{{ Storage::url($this->trainer->photo) }}" alt="{{ $name }}" class="h-full w-full object-cover">
                        @else
                            <span class="grid h-full w-full place-items-center font-display text-xl font-bold text-brand-700">{{ strtoupper(substr($name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Profile photo</p>
                        <label class="btn-secondary mt-2 cursor-pointer text-xs">
                            Choose photo
                            <input type="file" wire:model="photo" class="hidden">
                        </label>
                        @error('photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="text-sm font-medium text-slate-700">Full name</label>
                        <input id="name" type="text" wire:model="name" class="input mt-1.5">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="text-sm font-medium text-slate-700">Phone</label>
                        <input id="phone" type="text" wire:model="phone" class="input mt-1.5">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expertise" class="text-sm font-medium text-slate-700">Expertise</label>
                        <input id="expertise" type="text" wire:model="expertise" class="input mt-1.5" placeholder="e.g. Web Development">
                    </div>
                    <div>
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input id="email" type="email" value="{{ auth()->user()->email }}" disabled class="input mt-1.5 cursor-not-allowed bg-slate-50">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="bio" class="text-sm font-medium text-slate-700">Bio</label>
                        <textarea id="bio" rows="3" wire:model="bio" class="input mt-1.5" placeholder="Short introduction shown on the public trainer page"></textarea>
                        @error('bio')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <div>
            <form wire:submit="changePassword" class="card p-6">
                <h3 class="font-display text-base font-bold text-slate-900">Change Password</h3>
                <p class="mt-1 text-sm text-slate-500">Use at least 8 characters.</p>

                <div class="mt-6 space-y-4">
                    <div>
                        <label for="current_password" class="text-sm font-medium text-slate-700">Current password</label>
                        <input id="current_password" type="password" wire:model="current_password" class="input mt-1.5">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="new_password" class="text-sm font-medium text-slate-700">New password</label>
                        <input id="new_password" type="password" wire:model="new_password" class="input mt-1.5">
                        @error('new_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="text-sm font-medium text-slate-700">Confirm new password</label>
                        <input id="new_password_confirmation" type="password" wire:model="new_password_confirmation" class="input mt-1.5">
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    <button type="submit" class="btn-primary w-full">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>