<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <!-- Appearance Settings Section -->
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
                {{ __('Appearance') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Update the appearance settings for your account') }}
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
                          {{ request()->url() === $item['route'] ? 'bg-[#e50914] text-white' : 'bg-gray-100 dark:bg-[#1f1f1f] text-gray-800 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition' }}">
                    {{ __($item['name']) }}
                </a>
            @endforeach
        </div>

        <!-- Appearance Form Card -->
        <div class="bg-white dark:bg-[#141414] rounded-2xl shadow-lg p-8 md:p-10 border border-gray-300 dark:border-gray-800">
            <form wire:submit.prevent="updateAppearance" class="space-y-6">
                @csrf
                <!-- Appearance Radio Buttons -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-300 mb-3">
                        {{ __('Select Theme') }}
                    </label>
                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="flex gap-4">
                        <flux:radio value="light" icon="sun" class="px-4 py-2 bg-gray-100 dark:bg-[#1f1f1f] text-gray-900 dark:text-white rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            {{ __('Light') }}
                        </flux:radio>
                        <flux:radio value="dark" icon="moon" class="px-4 py-2 bg-gray-100 dark:bg-[#1f1f1f] text-gray-900 dark:text-white rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            {{ __('Dark') }}
                        </flux:radio>
                        <flux:radio value="system" icon="computer-desktop" class="px-4 py-2 bg-gray-100 dark:bg-[#1f1f1f] text-gray-900 dark:text-white rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            {{ __('System') }}
                        </flux:radio>
                    </flux:radio.group>
                </div>

                <!-- Save Button -->
                <div class="flex items-center justify-start mt-6">
                    <button type="submit"
                            class="px-6 py-3 rounded-full font-semibold shadow-lg
                                   text-white transition transform hover:scale-105
                                   bg-gradient-to-r from-[#e50914] to-[#ff3d5f]">
                        {{ __('Save Changes') }}
                    </button>

                    <x-action-message class="text-green-500 font-medium ml-4" on="appearance-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>
    </section>
</div>
