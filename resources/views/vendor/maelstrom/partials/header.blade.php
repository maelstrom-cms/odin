@auth()
    <header class="logged-in js-header flex shadow border-bottom-default border-dark py-3 pl-4 items-center justify-between">
        <div class="logo flex items-center">
            @include('maelstrom::partials.header-logo')
        </div>
        <div class="pr-6 flex items-center">
            @include('maelstrom::partials.header-nav')
        </div>
    </header>
@else
    <header class="guest js-header shadow bg-white border-bottom-default border-dark py-3 flex items-center justify-center">
        <div class="logo">
            @include('maelstrom::partials.header-logo')
        </div>
    </header>
@endauth
