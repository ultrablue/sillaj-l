@extends('layouts.app')

@section('content')

    {{-- The form title. Should this be something else? Another type of markup I mean? --}}
    <div class="text-2xl">{{ __('Login') }} 💕</div>


    <div class="w-full max-w-xs">

    <form method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('E-Mail Address') }}</label>

            <div class="">
                <input id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('email') ? ' border-red-500' : '' }}" name="email" value="{{ old('email') }}" autofocus>

                @if ($errors->has('email'))
                    <p class="text-red-500 text-xs italic">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                <input id="password" type="password" class=" shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('password') ? ' border-red-500' : '' }}" name="password">
                @if ($errors->has('password'))
                <p class="text-red-500 text-xs italic">
                    {{ $errors->first('password') }}
                </p>
                @endif            
        </div>

        <div class="mb-4">
                    <label class=" block text-gray-700">
                        <input type="checkbox" name="remember" class="mr-2 leading-tight" {{ old('remember') ? 'checked' : '' }}> 
                        <span class="text-sm">{{ __('Remember Me') }}</span>
                    </label>
        </div>

        <div class="flex items-center justify-between">
            
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('Login') }}
                </button>

                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            
        </div>
    </form>
</div>

@endsection
