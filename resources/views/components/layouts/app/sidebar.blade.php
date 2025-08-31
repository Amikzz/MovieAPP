<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <title>MovieApp</title>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">

{{-- Main content only --}}
<div class="w-full min-h-screen">
    {{ $slot }}
</div>

@fluxScripts
</body>
</html>
