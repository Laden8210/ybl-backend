@extends('layouts.app')

@section('content')
<section class="py-5 section-bg">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold text-heading mb-3">Yellow Bus Line</h1>
                <p class="lead text-secondary-body mb-4">Reliable, safe, and efficient public transport. Real-time GPS tracking, clear communication, and smooth drop-off coordination for everyone.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
                    <a href="#features" class="btn btn-outline-primary btn-lg">Explore Features</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-1">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#2D9C6E">GPS</div>
                                <div class="small text-secondary-body">Real-time</div>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#4A90E2">Routes</div>
                                <div class="small text-secondary-body">Smart Maps</div>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#FF8C42">Alerts</div>
                                <div class="small text-secondary-body">On-time</div>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#FDB913">Departures</div>
                                <div class="small text-secondary-body">Schedules</div>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#1E3A5F">Security</div>
                                <div class="small text-secondary-body">Trusted</div>
                            </div>
                            <div class="col-6 col-md-4 mb-3">
                                <div class="fw-bold" style="color:#FFD54F">Support</div>
                                <div class="small text-secondary-body">24/7</div>
                            </div>
                        </div>
                        <div class="mt-3 small text-secondary-body">Built for Admins, Supervisors, Drivers, Conductors, and Passengers.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5">
    <div class="container">
        <h2 class="h3 text-heading mb-4">Key Features</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5 text-heading">Real-Time Tracking</h3>
                        <p class="text-secondary-body mb-0">Monitor bus locations and drop-off points as they happen.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5 text-heading">Clear Communication</h3>
                        <p class="text-secondary-body mb-0">Passenger → Conductor → Driver workflow for accuracy.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5 text-heading">Role-Based Access</h3>
                        <p class="text-secondary-body mb-0">Admin, Supervisor, Driver, Conductor, and Passenger views.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="py-5 section-bg">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="h4 text-heading mb-1">Need help?</h2>
                <p class="text-secondary-body mb-0">Reach out to our support team and we’ll get back quickly.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="mailto:support@ybl.example" class="btn btn-secondary">Contact Support</a>
            </div>
        </div>
    </div>
</section>
@endsection
