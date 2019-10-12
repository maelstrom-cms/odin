@include('maelstrom::inputs.text', [
    'name' => 'url',
    'label' => 'Website URL',
    'html_type' => 'url',
    'prefix' => '🔗',
    'help' => 'Please provide the full website url including protocol e.g. https://www.mywebsite.com',
    'required' => true,
])

<div class="w-2/3">

    <div class="flex flex-wrap justify-between">
        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'uptime_enabled',
                'label' => 'Enable Up-Time Monitoring?',
                'hide_off' => ['uptime_keyword'],
            ])
        </div>

        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'ssl_enabled',
                'label' => 'Enable SSL Monitoring?'
            ])
        </div>

        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'robots_enabled',
                'label' => 'Enable Robots.txt Monitoring?'
            ])
        </div>
    </div>

    <div class="mt-5 flex flex-wrap justify-between">
        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'dns_enabled',
                'label' => 'Enable DNS Monitoring?'
            ])
        </div>

        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'cron_enabled',
                'label' => 'Enable Cron Monitoring?',
                'hide_off' => ['cron_key', 'cron_info'],
            ])
        </div>

        <div class="w-1/3">
            @include('maelstrom::inputs.switch', [
                'name' => 'crawler_enabled',
                'label' => 'Enable Crawler?'
            ])
        </div>
    </div>
</div>

@include('maelstrom::inputs.text', [
    'name' => 'uptime_keyword',
    'label' => 'Uptime Keyword',
    'help' => 'This word *must* exist on the web page to confirm the site is online.',
    'prefix' => '🔑',
    'required' => true,
])

@php($cronKey = data_get($entry, 'cron_key', Str::random(32)))

@include('maelstrom::inputs.random', [
    'name' => 'cron_key',
    'label' => 'Cron API Key',
    'prefix' => '🔒',
    'required' => true,
    'default' => $cronKey,
    'length' => 32,
    'charset' => 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM',
])

@if($entry)
<div id="cron_info_field" class="cloak">
    <p>
        When your scheduled task starts, you should ping:
        <pre><code>{{ route('ping.start', ['website' => $entry, 'key' => $cronKey]) }}</code></pre>
    </p>

    <p>
        When your scheduled task finishes, you should ping:
        <pre><code>{{ route('ping.stop', ['website' => $entry, 'key' => $cronKey]) }}</code></pre>
    </p>

    <p>
        You can append extra query strings to the URL to help identify your events. e.g.
        <pre><code>{{ route('ping.stop', ['website' => $entry, 'task' => 'Optimise-Images']) }}</code></pre>
    </p>
</div>
@else
    <div id="cron_info_field" class="cloak">
        <p>Once you've "Saved" this website, we'll provide you with your ping endpoints.</p>
    </div>
@endif
