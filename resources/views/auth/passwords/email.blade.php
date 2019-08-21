@extends('maelstrom::layouts.wrapper')

@push('head_after')
    <style> body { background: #f7fafc; }</style>
@endpush

@section('title')
    Reset Password
@endsection

@section('main')
    <div data-aos="fade-in" data-aos-delay="100" data-aos-duration="1000" class="w-full max-w-xs mx-auto mt-24">

        @include('maelstrom::components.loader')

        @if (session()->has('status'))
            <div class="pb-10">
                @include('maelstrom::components.alert', ['message' => session()->get('status')])
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="cloak bg-white shadow-md rounded px-8 pt-6 mb-4">
            @csrf()

            @include('maelstrom::inputs.text', [
                'name' => 'email',
                'label' => 'Email Address',
                'html_type' => 'email',
                'required' => true,
            ])

            <div class="mt-10 flex justify-between items-center">
                @include('maelstrom::buttons.button', [
                    'label' => 'Send Reset Email',
                    'type' => 'primary',
                ])
                <div>
                    <a class="inline-block" style="margin-bottom: 24px;" href="/login">< Login</a>
                </div>
            </div>
        </form>

        <p class="mt-10 text-center text-gray-500 text-xs">
            &copy;{{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>

    </div>
@endsection
