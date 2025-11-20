@extends('layouts.admin')

@section('title', 'Trips')
@section('page_title', 'Trip Management')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-secondary-body">All Trips</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Route</th>
                            <th>Bus</th>
                            <th>Driver</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                            <tr>
                                <td class="ps-4 fw-semibold">#{{ $trip->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $trip->route->name ?? 'N/A' }}</span>
                                        <span class="small text-muted">{{ $trip->route->origin ?? '-' }} <i class="bi bi-arrow-right mx-1"></i> {{ $trip->route->destination ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($trip->bus)
                                        <span class="badge bg-light text-dark border">
                                            {{ $trip->bus->bus_number }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trip->driver)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary small">
                                                {{ substr($trip->driver->name, 0, 1) }}
                                            </div>
                                            <span>{{ $trip->driver->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $trip->trip_date->format('M d, Y') }}</span>
                                        <span class="small text-muted">{{ $trip->actual_departure_time ? $trip->actual_departure_time->format('h:i A') : ($trip->schedule->departure_time ? $trip->schedule->departure_time->format('h:i A') : '-') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($trip->status) {
                                            'completed' => 'success',
                                            'in_progress' => 'warning',
                                            'scheduled' => 'primary',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusLabel = ucwords(str_replace('_', ' ', $trip->status));
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.trips.show', $trip) }}" class="btn btn-sm btn-outline-primary">
                                        View Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                    No trips found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $trips->links() }}
        </div>
    </div>
@endsection
