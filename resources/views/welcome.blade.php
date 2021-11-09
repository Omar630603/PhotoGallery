@extends('layouts.app')
@section('content')
<div class="welcome">
    {{-- <img src="{{Storage::disk('oci')->url('grk5mithwnvq.jpg')}}"> --}}

    <div class="header">
        <h1 class="align-text-bottom">Welcom to PhotoGallery</h1>
        <img class="img-fluid" src="{{ asset('images\icon.png') }}" alt="">
    </div>
    <div class="sub-header">
        <h2>Upload your photos to the cloud</h2>
        <p>Safe and Secure</p>
    </div>
    @guest
    <div class="signInSignUp">
        @if (Route::has('login'))
        <div class="rowSignInSignUp">
            <p>Do you have an account?</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">{{ __('Login') }}</a>
        </div>
        @endif

        @if (Route::has('register'))
        <div class="rowSignInSignUp">
            <p>Ohh if you don't, you can</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('register') }}">{{ __('Register') }}</a>
        </div>
        @endif
    </div>
    @else
    <div class="home-profile">
        <a class="btn btn-outline-light btn-sm" href="{{ route('home') }}">
            Profile:{{ Auth::user()->name }}
        </a>
        <a class="btn btn-outline-danger btn-sm" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    @endguest
    <div class="welcome-img mt-2">
        <img class="img-fluid" src="{{ asset('images\welcome.png') }}" alt="">
    </div>
</div>
@endsection