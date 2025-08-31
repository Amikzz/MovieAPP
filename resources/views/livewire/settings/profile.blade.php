<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    <!-- Profile Settings Section -->
    <section class="w-full px-6 md:px-12 lg:px-24 py-10">

        <!-- Top Buttons: Back & Home -->
        <div class="flex gap-3 mb-6">
            <!-- Back Button -->
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow hover:bg-red-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 14.707a1 1 0 01-1.414 0L7 10.414l4.293-4.293a1 1 0 011.414 1.414L10.414 10l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('Back') }}
            </a>

            <!-- Home Button -->
            <a href="{{ url('/dashboard') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-900 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h16V10"/>
                </svg>
                {{ __('Home') }}
            </a>
        </div>

        <!-- Page Heading -->
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                {{ __('Profile Settings') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Update your name and email address') }}
            </p>
        </div>

        <!-- Settings Nav -->
        <div class="flex gap-4 mb-8 justify-center md:justify-start flex-wrap md:flex-nowrap">
            @php
                $navItems = [
                    ['name' => 'Profile', 'route' => route('settings.profile')],
                    ['name' => 'Password', 'route' => route('settings.password')],
                    ['name' => 'Appearance', 'route' => route('settings.appearance')],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}"
                   class="px-4 py-2 rounded-xl font-semibold
                          {{ request()->url() === $item['route'] ? 'bg-[#e50914] text-white' : 'bg-gray-100 dark:bg-[#1f1f1f] text-gray-900 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition' }}">
                    {{ __($item['name']) }}
                </a>
            @endforeach
        </div>

        <!-- Profile Form Card -->
        <div class="bg-white dark:bg-[#141414] rounded-2xl shadow-lg p-8 md:p-10 border border-gray-300 dark:border-gray-800">
            <form wire:submit="updateProfileInformation" class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-800 dark:text-gray-300 mb-2">
                        {{ __('Name') }}
                    </label>
                    <input wire:model="name" type="text" id="name" required
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-[#1f1f1f] border border-gray-300 dark:border-gray-700
                                  text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-[#e50914]
                                  focus:border-[#e50914] transition">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-800 dark:text-gray-300 mb-2">
                        {{ __('Email') }}
                    </label>
                    <input wire:model="email" type="email" id="email" required readonly
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-[#1f1f1f] border border-gray-300 dark:border-gray-700
                                  text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-[#e50914]
                                  focus:border-[#e50914] transition">

                    <!-- Verification Notice -->
                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                        <div class="mt-4 text-gray-600 dark:text-gray-400 text-sm">
                            {{ __('Your email address is unverified.') }}
                            <button wire:click.prevent="resendVerificationNotification"
                                    class="ml-2 underline text-[#e50914] hover:text-[#ff3d5f] transition">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-green-500 font-medium">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Save Button + Status -->
                <div class="flex items-center justify-between mt-6">
                    <button type="submit"
                            class="px-6 py-3 rounded-full font-semibold shadow-lg
                                   text-white transition transform hover:scale-105
                                   bg-gradient-to-r from-[#e50914] to-[#ff3d5f]">
                        {{ __('Save Changes') }}
                    </button>

                    <x-action-message class="text-green-500 font-medium" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>

        <!-- Danger Zone (Delete User) -->
        <div class="mt-10 bg-gray-100 dark:bg-[#1f1f1f] rounded-2xl p-6 border border-red-300 dark:border-red-800">
            <h2 class="text-xl font-bold text-red-600 dark:text-red-500 mb-3">{{ __('Danger Zone') }}</h2>
            <p class="text-gray-700 dark:text-gray-400 mb-4">{{ __('Permanently delete your account and all data.') }}</p>
            <livewire:settings.delete-user-form />
        </div>
    </section>
</div>
