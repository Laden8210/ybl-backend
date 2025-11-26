@extends('layouts.admin')

@section('title', 'Live Tracking')
@section('page_title', 'Live Tracking')

@push('head')
    <style>
        #map {
            height: calc(100vh - 180px);
            width: 100%;
            border-radius: 0.5rem;
            z-index: 1;
        }
        .bus-list {
            height: calc(100vh - 180px);
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Active Buses ({{ $activeTrips->count() }})</h6>
                </div>
                <div class="card-body p-0 bus-list">
                    <div class="list-group list-group-flush">
                        @forelse($activeTrips as $trip)
                            <a href="#" class="list-group-item list-group-item-action p-3" onclick="focusBus({{ $trip->latestLocation->latitude ?? 0 }}, {{ $trip->latestLocation->longitude ?? 0 }})">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="fw-bold">Bus {{ $trip->bus?->bus_number ?? 'N/A' }}</div>
                                    <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                </div>
                                <div class="small text-muted mb-2">
                                    {{ $trip->route->name ?? 'Unknown Route' }}
                                </div>
                                <div class="d-flex align-items-center gap-2 small text-secondary">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>{{ $trip->latestLocation->speed ?? 0 }} km/h</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-bus-front display-6 d-block mb-2"></i>
                                No active buses.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on a default location (e.g., General Santos City)
            const map = L.map('map').setView([6.1164, 125.1716], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Bus Icon
            const busIcon = L.divIcon({
                html: '<div style="background-color: #FFD54F; border: 2px solid #fff; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"><i class="bi bi-bus-front-fill" style="color: #333; font-size: 16px;"></i></div>',
                className: 'custom-bus-marker',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            // Add markers for active trips
            const trips = @json($activeTrips);
            
            trips.forEach(trip => {
                if (trip.latest_location) {
                    const lat = parseFloat(trip.latest_location.latitude);
                    const lng = parseFloat(trip.latest_location.longitude);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        L.marker([lat, lng], {icon: busIcon})
                            .addTo(map)
                            .bindPopup(`
                                <strong>Bus ${trip.bus ? trip.bus.bus_number : 'N/A'}</strong><br>
                                Route: ${trip.route ? trip.route.name : 'N/A'}<br>
                                Speed: ${trip.latest_location.speed} km/h
                            `);
                    }
                }
            });

            window.focusBus = function(lat, lng) {
                if (lat && lng) {
                    map.flyTo([lat, lng], 15);
                }
            }
        });
    </script>
@endpush
