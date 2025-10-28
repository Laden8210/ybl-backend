@extends('layouts.app')

@section('content')
<section class="py-5 section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 text-heading mb-1">Welcome back</h1>
                        <p class="text-secondary-body mb-4">Sign in to continue to Yellow Bus Line.</p>

                        <form method="POST" action="#" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
                                <div class="form-text">We'll never share your email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="#" class="link-primary" style="color:#4A90E2">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-secondary-body">Need an account? <a href="#" class="link-primary" style="color:#4A90E2">Contact admin</a></small>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="/" class="btn btn-outline-primary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
