@extends('maelstrom::layouts.form')

@section('title')
    Monitor for: {{ $entry->url }}
@endsection

@section('content')

    @component('maelstrom::components.tabs')

        @component('maelstrom::components.tab', ['label' => 'Monitor'])
            <div class="mb-10" data-component="OpenGraph" data-website='@json($entry)' data-endpoint="{{ route('opengraph', $entry) }}"></div>

            @if ($entry->uptime_enabled)
                <div class="mb-10" data-component="UptimeReport" data-website='@json($entry)' data-endpoint="{{ route('uptime', $entry) }}"></div>
            @endif

            @if ($entry->ssl_enabled)
                <div class="mb-10" data-component="CertificateReport" data-website='@json($entry)' data-endpoint="{{ route('ssl', $entry) }}"></div>
            @endif

            @if ($entry->robots_enabled)
                <div class="mb-10" data-component="RobotsReport" data-website='@json($entry)' data-endpoint="{{ route('robots', $entry) }}"></div>
            @endif

            @if ($entry->dns_enabled)
                <div class="mb-10" data-component="DnsReport" data-website='@json($entry)' data-endpoint="{{ route('dns', $entry) }}"></div>
            @endif

            @if ($entry->cron_enabled)
                <div class="mb-10" data-component="CronReport" data-website='@json($entry)' data-endpoint="{{ route('crons', $entry) }}"></div>
            @endif

            @if ($entry->crawler_enabled)
                <div class="mb-10" data-component="CrawlReport" data-website='@json($entry)' data-endpoint="{{ route('problems', $entry) }}"></div>
            @endif
        @endcomponent

        @component('maelstrom::components.form', [
            'action' => $action,
            'method' => $method,
        ])
            @component('maelstrom::components.tab', ['label' => 'Settings'])
                @include('websites-form', ['entry' => $entry])
            @endcomponent
        @endcomponent

    @endcomponent

@endsection
