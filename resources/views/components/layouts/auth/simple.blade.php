<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
<div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
    <div class="flex w-full max-w-sm flex-col gap-6 items-center">
        <!-- Centered Logo -->
        <a href="{{ url('/') }}"
           class="text-3xl font-extrabold tracking-tight text-white hover:text-[#e50914] transition text-center">
            Movie<span class="text-[#e50914]">App</span>
        </a>

        <!-- Slot for forms -->
        <div class="flex flex-col gap-6 w-full">
            {{ $slot }}
        </div>
    </div>
</div>
@fluxScripts
</body>
</html>
