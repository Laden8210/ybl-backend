@extends('layouts.admin')

@section('title', 'Route Details - ' . $route->route_name)
@section('page_title', 'Route Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Routes
            </a>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Edit Route
            </a>
            <form action="{{ route('admin.routes.toggle-status', $route) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $route->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                    <i class="bi {{ $route->is_active ? 'bi-pause' : 'bi-play' }} me-1"></i>
                    {{ $route->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Route Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-4 px-2 py-1">
                            <i class="bi bi-signpost text-white fs-4"></i>
                        </div>
                        <div>
                            <h1 class="h3 mb-1">{{ $route->route_name }}</h1>
                            @if($route->description)
                                <p class="text-muted mb-2">{{ $route->description }}</p>
                            @endif
                            <div class="d-flex gap-3">
                                <span class="badge {{ $route->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                                    {{ $route->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="badge bg-info fs-6">
                                    <i class="bi bi-arrow-left-right me-1"></i>{{ $route->formatted_distance }}
                                </span>
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-clock me-1"></i>{{ $route->formatted_duration }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="text-muted">
                        <small>Created: {{ $route->created_at->format('M d, Y') }}</small><br>
                        <small>Last Updated: {{ $route->updated_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Route Information -->
        <div class="col-lg-4">
            <!-- Route Points Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Route Points</h5>
                </div>
                <div class="card-body">
                    <!-- Start Point -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3 mt-1 px-2 py-1">
                            <i class="bi bi-play-fill text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Start Point</h6>
                            <p class="mb-1 text-dark fw-semibold">{{ $route->start_point }}</p>
                            <small class="text-muted">
                                <i class="bi bi-geo me-1"></i>
                                {{ number_format($route->start_latitude, 6) }}, {{ number_format($route->start_longitude, 6) }}
                            </small>
                        </div>
                    </div>

                    <!-- Waypoints -->
                    @php
                        $waypoints = $route->waypoints ? json_decode($route->waypoints, true) : [];
                    @endphp

                    @if(count($waypoints) > 0)
                        @foreach($waypoints as $waypoint)
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3 mt-1 px-2 py-1">
                                <span class="text-white fw-bold small">{{ $waypoint['sequence'] }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Stop {{ $waypoint['sequence'] }}</h6>
                                <p class="mb-1 text-dark fw-semibold">{{ $waypoint['name'] }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-geo me-1"></i>
                                    {{ number_format($waypoint['latitude'], 6) }}, {{ number_format($waypoint['longitude'], 6) }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <!-- End Point -->
                    <div class="d-flex align-items-start">
                        <div class="avatar-sm bg-danger rounded-circle d-flex align-items-center justify-content-center me-3 mt-1 px-2 py-1">
                            <i class="bi bi-flag-fill text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">End Point</h6>
                            <p class="mb-1 text-dark fw-semibold">{{ $route->end_point }}</p>
                            <small class="text-muted">
                                <i class="bi bi-geo me-1"></i>
                                {{ number_format($route->end_latitude, 6) }}, {{ number_format($route->end_longitude, 6) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route Statistics -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Route Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="bi bi-arrow-left-right text-primary fs-4 d-block mb-2"></i>
                                <h4 class="mb-1">{{ $route->formatted_distance }}</h4>
                                <small class="text-muted">Total Distance</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="bi bi-clock text-warning fs-4 d-block mb-2"></i>
                                <h4 class="mb-1">{{ $route->formatted_duration }}</h4>
                                <small class="text-muted">Estimated Duration</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <i class="bi bi-signpost text-info fs-4 d-block mb-2"></i>
                                <h4 class="mb-1">{{ count($waypoints) }}</h4>
                                <small class="text-muted">Intermediate Stops</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <i class="bi bi-calendar-check text-success fs-4 d-block mb-2"></i>
                                <h4 class="mb-1">{{ $route->schedules->count() }}</h4>
                                <small class="text-muted">Active Schedules</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Map and Schedules -->
        <div class="col-lg-8">
            <!-- Route Map -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-map me-2"></i>Route Map</h5>
                </div>
                <div class="card-body p-0">
                    <div id="route-map" style="height: 400px; border-radius: 0 0 8px 8px;"></div>
                </div>
            </div>

            <!-- Schedules Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-calendar me-2"></i>Route Schedules</h5>
                        <span class="badge bg-light text-dark">{{ $route->schedules->count() }} schedules</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($route->schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bus</th>
                                        <th>Departure Time</th>
                                        <th>Arrival Time</th>
                                        <th>Day</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($route->schedules as $schedule)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-bus-front text-white"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $schedule->bus->bus_number }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $schedule->bus->license_plate }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark text-capitalize">
                                                {{ $schedule->day_of_week }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $schedule->status === 'scheduled' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Schedules Found</h5>
                            <p class="text-muted">This route doesn't have any schedules yet.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Create Schedule
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 24px;
}
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
.route-point {
    border-left: 3px solid #007bff;
    padding-left: 15px;
    margin-bottom: 20px;
}
.route-point.start {
    border-left-color: #28a745;
}
.route-point.end {
    border-left-color: #dc3545;
}
.stat-card {
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('route-map').setView([{{ $route->start_latitude }}, {{ $route->start_longitude }}], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add scale control
    L.control.scale({ imperial: false }).addTo(map);

    // Create waypoints array for routing
    const waypoints = [
        L.latLng({{ $route->start_latitude }}, {{ $route->start_longitude }})
    ];

    // Add intermediate waypoints
    @php
        $waypoints = $route->waypoints ? json_decode($route->waypoints, true) : [];
    @endphp

    @foreach($waypoints as $waypoint)
        waypoints.push(L.latLng({{ $waypoint['latitude'] }}, {{ $waypoint['longitude'] }}));
    @endforeach

    // Add end point
    waypoints.push(L.latLng({{ $route->end_latitude }}, {{ $route->end_longitude }}));

    // Create markers for all points
    // Start marker
    const startMarker = L.marker(waypoints[0], {
        icon: L.divIcon({
            className: 'start-marker',
            html: '<div style="background-color: #28a745; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">S</div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(map).bindPopup(`
        <div class="text-center">
            <strong>Start Point</strong><br>
            {{ $route->start_point }}<br>
            <small class="text-muted">
                {{ number_format($route->start_latitude, 6) }}, {{ number_format($route->start_longitude, 6) }}
            </small>
        </div>
    `);

    // Waypoint markers
    @foreach($waypoints as $index => $waypoint)
        @if($index > 0 && $index < count($waypoints) - 1)
        L.marker(waypoints[{{ $index }}], {
            icon: L.divIcon({
                className: 'waypoint-marker',
                html: '<div style="background-color: #007bff; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px;">{{ $index }}</div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        }).addTo(map).bindPopup(`
            <div class="text-center">
                <strong>Stop {{ $index }}</strong><br>
                {{ $waypoint['name'] }}<br>
                <small class="text-muted">
                    {{ number_format($waypoint['latitude'], 6) }}, {{ number_format($waypoint['longitude'], 6) }}
                </small>
            </div>
        `);
        @endif
    @endforeach

    // End marker
    const endMarker = L.marker(waypoints[waypoints.length - 1], {
        icon: L.divIcon({
            className: 'end-marker',
            html: '<div style="background-color: #dc3545; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">E</div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(map).bindPopup(`
        <div class="text-center">
            <strong>End Point</strong><br>
            {{ $route->end_point }}<br>
            <small class="text-muted">
                {{ number_format($route->end_latitude, 6) }}, {{ number_format($route->end_longitude, 6) }}
            </small>
        </div>
    `);

    // Create routing control
    const routingControl = L.Routing.control({
        waypoints: waypoints,
        routeWhileDragging: false,
        showAlternatives: false,
        lineOptions: {
            styles: [{ color: '#007bff', weight: 6, opacity: 0.8 }]
        },
        createMarker: function() { return null; } // Don't create default markers
    }).addTo(map);

    // Fit map to show the entire route
    const bounds = L.latLngBounds(waypoints);
    map.fitBounds(bounds, { padding: [20, 20] });

    // Add legend
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'info legend bg-white p-3 rounded shadow-sm');
        div.innerHTML = `
            <h6 class="mb-2">Route Legend</h6>
            <div class="d-flex align-items-center mb-1">
                <div style="background-color: #28a745; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
                <small>Start Point</small>
            </div>
            <div class="d-flex align-items-center mb-1">
                <div style="background-color: #007bff; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
                <small>Intermediate Stops</small>
            </div>
            <div class="d-flex align-items-center">
                <div style="background-color: #dc3545; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></div>
                <small>End Point</small>
            </div>
        `;
        return div;
    };
    legend.addTo(map);
});
</script>
@endpush
