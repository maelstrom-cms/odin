<?php

return [
    /*
     * Displays in the logo area.
     */
    'title' => env('APP_NAME'),

    /*
     * The root path of your control panel.
     */
    'path' => '/',

    /*
     * This will be prepended to the start of all breadcrumbs.
     */
    'breadcrumb' => [],

    /*
     * You can set some base sidebar items here, nothing fancy, however
     * we recommend defining this somewhere else so you can use things
     * such as the route() helper, we just need to globally expose a variable
     * called $maelstrom_sidebar which we can do inside the AppServiceProviders boot method
     *
     * e.g. View::share('maelstrom_sidebar', [ ...items ])
     */
    'sidebar' => [],

    /*
     * "light" or "dark" - This gets passed to ant design where possible.
     */
    'theme' => 'dark',

    /*
     * Where does your asset pipeline output our JS?
     * (relative to the public folder)
     */
    'core_js_path' => '/js/maelstrom.js',

    /*
     * Where does your asset pipeline output our CSS?
     * (relative to the public folder)
     */
    'core_css_path' => '/css/maelstrom.css',

    /*
     * Will include custom css files after maelstrom.css on every page.
     */
    'custom_css' => [],

    /*
     * Will include custom js files after maelstrom.js on every page.
     *
     * If you want automatic cache busting AND config caching
     * You'll need to push to the stack `footer_after` or
     * publish the `maelstrom:partials/footer-scripts.blade.php`
     * and use the `mix()` helper in there.
     */
    'custom_js' => [],

    /*
     * Although we use the IoC container to allow you to overwrite
     * which panel we load, you can also define your custom root panel here.
     */
    'panel' => \Maelstrom\Panel::class,

    /*
     * If you need some basic authentication, we've got some bits for you.
     * Use as much or as little as you need.
     */
    'auth' => [
        /*
         * If you want the built in authentication features,
         * set to false if you want to disable it.
         */
        'enabled' => true,

        /*
         * If your using a custom auth guard, you can define it here.
         */
        'guard' => 'web',

        /*
         * If you need to protect this endpoint at route level or anything else
         * you can provide some middleware, which can abort(401) the request.
         */
        'middleware' => ['web'],

        /*
         * We use the current user in "some" places - mostly on the
         * "edit my account" page, if you use the built in controller
         * then you can change the model here.
         */
        'model' => \App\User::class,
    ],

    /*
     * We provide a form of nested resources, that allow you to
     * pick related entities e.g. categories, we have a little
     * automated system which can help you with this which can
     * be configured below.
     */
    'form_options' => [
        /*
         * If you want the automatic form options route to register,
         * set to false if you want to disable it or provide your own routes.
         */
        'enabled' => true,

        /*
         * If your using a custom auth guard, you can define it here.
         */
        'guard' => 'web',

        /*
         * If you need to protect this endpoint at route level or anything else
         * you can provide some middleware, which can abort(401) the request.
         */
        'middleware' => ['web'],

        /*
         * These form options will be included in the AJAX endpoint.
         * We just need to know the "name" of the set, then which model
         * it is you're wanting to return values of, any applied scopes
         * where the value field should draw from and which field should be the
         * name/label field.
         *
         * 'categories' => [
         *     'model' => App\Category::class,
         *     'scopes' => ['customScope'],
         *     'value' => 'id',
         *     'label' => 'name',
         * ],
         *
         */
        'models' => [],
    ],

    /*
     * Our media manager is a simple tool to attach
     * single or multiple media items to another entity,
     * it provides back the ID of the related media
     * which you can handle however you like, the options
     * are listed below.
     */
    'media_manager' => [
        /*
         * If you want the automatic form options route to register,
         * set to false if you want to disable it or provide your own routes.
         */
        'enabled' => true,

        /*
         * If your using a custom auth guard, you can define it here.
         */
        'guard' => 'web',

        /*
         * If you need to protect this endpoint at route level or anything else
         * you can provide some middleware, which can abort(401) the request.
         */
        'middleware' => ['web'],

        /*
         * Provide the disk from filesystems.php which will be
         * used to store the uploaded media.
         */
        'disk' => 'public',

        /*
         * If you need to inject a custom media class, you can do so - however make sure it
         * extends our base class, or copies the methods across.
         */
        'model' => \Maelstrom\Models\Media::class,

        /*
         * A list of accepted mime-types for the media uploader.
         * We use the symfony mime type detection for this, which
         * isn't always accurate, so be careful.
         */
        'mime_types' => [
            'image/svg',
            'image/svg+xml',
            'image/png',
            'image/jpeg',
            'application/pdf',
        ],

        /*
         * What dimensions should we make the thumbnails of uploaded assets?
         */
        'thumbnails' => [
            'width' => 300,
            'height' => 300,
        ],
    ]
];
