<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <!-- Password Settings Section -->
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
               class="px-4 py-2 bg-gray-800 text-white font-semibold rounded-lg shadow hover:bg-gray-900 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h16V10"/>
                </svg>
                {{ __('Home') }}
            </a>
        </div>

        <!-- Page Heading -->
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                {{ __('Update Password') }}
            </h1>
            <p class="text-gray-400 mt-2">
                {{ __('Ensure your account is using a long, random password to stay secure') }}
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
                          {{ request()->url() === $item['route'] ? 'bg-[#e50914] text-white' : 'bg-[#1f1f1f] text-gray-400 hover:bg-gray-700 hover:text-white transition' }}">
                    {{ __($item['name']) }}
                </a>
            @endforeach
        </div>

        <!-- Password Form Card -->
        <div class="bg-[#141414] rounded-2xl shadow-lg p-8 md:p-10 border border-gray-800">
            <form method="POST" wire:submit="updatePassword" class="space-y-6">
                @csrf
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-300 mb-2">
                        {{ __('Current Password') }}
                    </label>
                    <input wire:model="current_password" type="password" id="current_password" required
                           autocomplete="current-password"
                           class="w-full px-4 py-3 rounded-xl bg-[#1f1f1f] border border-gray-700
                                  text-white placeholder-gray-400 focus:ring-2 focus:ring-[#e50914]
                                  focus:border-[#e50914] transition">
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">
                        {{ __('New Password') }}
                    </label>
                    <input wire:model="password" type="password" id="password" required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-xl bg-[#1f1f1f] border border-gray-700
                                  text-white placeholder-gray-400 focus:ring-2 focus:ring-[#e50914]
                                  focus:border-[#e50914] transition">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">
                        {{ __('Confirm Password') }}
                    </label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-xl bg-[#1f1f1f] border border-gray-700
                                  text-white placeholder-gray-400 focus:ring-2 focus:ring-[#e50914]
                                  focus:border-[#e50914] transition">
                </div>

                <!-- Save Button + Status -->
                <div class="flex items-center justify-between mt-6">
                    <button type="submit"
                            class="px-6 py-3 rounded-full font-semibold shadow-lg
                                   text-white transition transform hover:scale-105
                                   bg-gradient-to-r from-[#e50914] to-[#ff3d5f]">
                        {{ __('Save Changes') }}
                    </button>

                    <x-action-message class="text-green-500 font-medium" on="password-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>
    </section>
</div>
