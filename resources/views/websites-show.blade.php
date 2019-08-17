@extends('maelstrom::layouts.basic', [
    'breadcrumbs' => [
        [
            'label' => 'Websites',
            'url' => route('websites.index'),
        ],
        [
            'label' => 'Monitor',
        ],
    ]
])

@section('title')
    Monitor for: {{ $website->url }}
@endsection

@section('content')

    @if ($website->robots_enabled)
    <div class="mb-10" data-component="RobotsReport" data-website='@json($website)'></div>
    @endif

    @if ($website->uptime_enabled)
    <div class="mb-10" data-component="UptimeReport" data-website='@json($website)'></div>
    @endif


    <div class="mb-10" data-component="CertificateReport" data-website='@json($website)'></div>
    <div class="mb-10" data-component="DnsReport" data-website='@json($website)'></div>

@endsection

@section('footer')
    @include('maelstrom::buttons.button', [
        'url' => route('websites.edit', $website),
        'label' => 'Change Settings'
    ])
@endsection
