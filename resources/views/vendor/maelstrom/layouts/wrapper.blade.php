<html lang="en">
<head>
    <title>@yield('title') :: {{ config('maelstrom.title_prefix', config('maelstrom.title')) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link id="favicon" rel="icon" type="image/png" href="/favicon.png">
    @include('maelstrom::partials.head-meta')
</head>
<body>
    <div class="maelstrom-wrapper{{ request()->has('iframe') ? ' iframe-mode' : '' }}">

        @include('maelstrom::partials.header')

        <main class="p-6">
            <div>
                @yield('main')
            </div>
        </main>

    </div>
    @include('maelstrom::partials.footer-scripts')
</body>
</html>
