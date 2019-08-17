@extends('maelstrom::layouts.form')

@section('content')

    @component('maelstrom::components.form', [
        'action' => $action,
        'method' => $method,
    ])

        @include('maelstrom::inputs.text', [
            'name' => 'url',
            'label' => 'Website URL',
            'html_type' => 'url',
            'prefix' => '🔗',
        ])

        <div class="flex flex-wrap justify-between">

            @include('maelstrom::inputs.switch', [
                'name' => 'ssl_enabled',
                'label' => 'Enable SSL Monitoring?'
            ])

            @include('maelstrom::inputs.switch', [
                'name' => 'uptime_enabled',
                'label' => 'Enable Up-Time Monitoring?'
            ])

            @include('maelstrom::inputs.switch', [
                'name' => 'robots_enabled',
                'label' => 'Enable Robots.txt Monitoring?'
            ])

            @include('maelstrom::inputs.switch', [
                'name' => 'dns_enabled',
                'label' => 'Enable DNS Monitoring?'
            ])
        </div>

    @endcomponent

@endsection
