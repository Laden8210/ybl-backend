@extends('layouts.admin')

@section('title', 'Trip Details')
@section('page_title', 'Trip Details #' . $trip->id)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Trips
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-lg-8">
            <!-- Trip Overview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Trip Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="p-3 rounded bg-light text-primary">
                                    <i class="bi bi-signpost-split fs-4"></i>
                                </div>
                                <div>
                                    <div class="small text-muted text-uppercase fw-bold">Route</div>
                                    <div class="fw-semibold fs-5">{{ $trip->route->name ?? 'N/A' }}</div>
                                    <div class="small text-secondary">
                                        {{ $trip->route->origin ?? '-' }} <i class="bi bi-arrow-right mx-1"></i> {{ $trip->route->destination ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="p-3 rounded bg-light text-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'in_progress' ? 'warning' : 'primary') }}">
                                    <i class="bi bi-activity fs-4"></i>
                                </div>
                                <div>
                                    <div class="small text-muted text-uppercase fw-bold">Status</div>
                                    <div class="fw-semibold fs-5 text-capitalize">{{ str_replace('_', ' ', $trip->status) }}</div>
                                    <div class="small text-secondary">
                                        {{ $trip->trip_date->format('F d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="row mt-3">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="small text-muted mb-1">Scheduled Departure</div>
                            <div class="fw-medium">{{ $trip->schedule->departure_time ? $trip->schedule->departure_time->format('h:i A') : '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="small text-muted mb-1">Actual Departure</div>
                            <div class="fw-medium">{{ $trip->actual_departure_time ? $trip->actual_departure_time->format('h:i A') : '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="small text-muted mb-1">Scheduled Arrival</div>
                            <div class="fw-medium">{{ $trip->schedule->arrival_time ? $trip->schedule->arrival_time->format('h:i A') : '-' }}</div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="small text-muted mb-1">Actual Arrival</div>
                            <div class="fw-medium">{{ $trip->actual_arrival_time ? $trip->actual_arrival_time->format('h:i A') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

 
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Bus Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Vehicle Information</h6>
                </div>
                <div class="card-body">
                    @if($trip->bus)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle bg-light text-dark">
                                <i class="bi bi-bus-front"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $trip->bus->bus_number }}</div>
                                <div class="small text-muted">{{ $trip->bus->plate_number }}</div>
                            </div>
                        </div>
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="text-muted">Capacity:</span>
                                <span class="fw-medium">{{ $trip->bus->capacity }}</span>
                            </div>
                   
                        </div>
                    @else
                        <div class="text-muted">No bus assigned.</div>
                    @endif
                </div>
            </div>

            <!-- Driver Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Driver Information</h6>
                </div>
                <div class="card-body">
                    @if($trip->driver)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                {{ substr($trip->driver->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $trip->driver->name }}</div>
                                <div class="small text-muted">{{ $trip->driver->email }}</div>
                            </div>
                        </div>
                       
                    @else
                        <div class="text-muted">No driver assigned.</div>
                    @endif
                </div>
            </div>

            <!-- Conductor Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Conductor Information</h6>
                </div>
                <div class="card-body">
                    @if($trip->busAssignment && $trip->busAssignment->conductor)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle bg-info bg-opacity-10 text-info">
                                {{ substr($trip->busAssignment->conductor->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $trip->busAssignment->conductor->name }}</div>
                                <div class="small text-muted">{{ $trip->busAssignment->conductor->email }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-muted">No conductor assigned.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
