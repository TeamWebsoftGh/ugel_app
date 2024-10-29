@extends('layouts.login')
@section("title", "Login")
@section('content')
    <div class="text-center">
        <div class="mb-4">
            <a href="/" class="d-block">
                <img src="{{asset(settings("logo"))}}" alt="" height="60">
            </a>
        </div>
        <h5 class="text-primary">Welcome Back!</h5>
    </div>

    <div class="mt-4">
        <form class="user" method="POST" action="{{route("login")}}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                @error('username')
                <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                @enderror
                @error('email')
                <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password-input">Password</label>
                <div class="position-relative auth-pass-inputgroup mb-3">
                    <input type="password" class="form-control pe-5" name="password" placeholder="Enter password" id="password-input">
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                </div>
                @error('password')
                <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>

            <div class="mt-4">
                <button class="btn btn-danger w-100" type="submit">Sign In</button>
            </div>
        </form>
    </div>

    @if (Route::has('password.request') && settings("enable_password_reset", false))
        <div class="mt-5 text-center">
            <p class="mb-0"> <a href="{{ route('password.request') }}" class="fw-semibold text-primary text-decoration-underline"> Forgot password?</a> </p>
        </div>
    @endif

@endsection
