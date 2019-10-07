@extends('maelstrom::layouts.wrapper')

@push('head_after')
    <style> body { background: #f7fafc; }</style>
@endpush

@section('title')
    Login
@endsection

@section('main')
    <div data-aos="fade-in" data-aos-delay="100" data-aos-duration="1000" class="w-full max-w-xs mx-auto mt-24">

        @include('maelstrom::components.loader')
        @include('maelstrom::components.flash')

        <form action="{{ route('login') }}" method="POST" class="cloak bg-white shadow-md rounded px-8 pt-6 mb-4">
            @csrf()

            @include('maelstrom::inputs.text', [
                'name' => 'email',
                'label' => 'Email Address',
                'html_type' => 'email',
                'required' => true,
                'default' => env('LOGIN_EMAIL'),
            ])

            @include('maelstrom::inputs.secret', [
                'name' => 'password',
                'label' => 'Password',
                'required' => true,
            ])

            <div class="mt-10">
                @include('maelstrom::buttons.button', [
                    'label' => 'Sign In',
                    'type' => 'primary',
                ])
            </div>

            <div class="mt-10">
                @if (Route::has('register'))
                <a class="inline-block" style="margin-bottom: 24px;" href="{{ route('register') }}">Register</a> or
                @endif
                <a class="inline-block" style="margin-bottom: 24px;" href="{{ route('password.request') }}">Reset Password</a>
            </div>
        </form>

        <p class="mt-10 text-center text-gray-500 text-xs">
            &copy;{{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>

    </div>
@endsection
