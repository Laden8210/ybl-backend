@extends('layouts.admin')

@section('title', 'Create Route')
@section('page_title', 'Create Route')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-signpost me-2"></i>Create New Route</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.routes.store') }}" method="POST" id="routeForm">
                        @csrf

                        <div class="row">
                            <!-- Left Column - Form Fields -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="route_name" class="form-label">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('route_name') is-invalid @enderror"
                                           id="route_name" name="route_name" value="{{ old('route_name') }}"
                                           placeholder="e.g., Downtown Express" required>
                                    @error('route_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Route description...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_point" class="form-label">Start Point <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt-fill text-success"></i></span>
                                                <input type="text" class="form-control @error('start_point') is-invalid @enderror"
                                                       id="start_point" name="start_point" value="{{ old('start_point') }}"
                                                       placeholder="e.g., Central Station" required>
                                            </div>
                                            @error('start_point')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" id="start_latitude" name="start_latitude" value="{{ old('start_latitude') }}">
                                            <input type="hidden" id="start_longitude" name="start_longitude" value="{{ old('start_longitude') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_point" class="form-label">End Point <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                                <input type="text" class="form-control @error('end_point') is-invalid @enderror"
                                                       id="end_point" name="end_point" value="{{ old('end_point') }}"
                                                       placeholder="e.g., City Mall" required>
                                            </div>
                                            @error('end_point')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" id="end_latitude" name="end_latitude" value="{{ old('end_latitude') }}">
                                            <input type="hidden" id="end_longitude" name="end_longitude" value="{{ old('end_longitude') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="distance" class="form-label">Distance (km) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                                <input type="number" class="form-control @error('distance') is-invalid @enderror"
                                                       id="distance" name="distance" value="{{ old('distance') }}"
                                                       step="0.01" min="0.01" required>
                                            </div>
                                            @error('distance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="estimated_duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror"
                                                       id="estimated_duration" name="estimated_duration" value="{{ old('estimated_duration') }}"
                                                       min="1" required>
                                            </div>
                                            @error('estimated_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Waypoints Section -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Waypoints (Intermediate Stops)</label>
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div id="waypoints-container" class="mb-3">
                                                <!-- Waypoints will be added here dynamically -->
                                                <div class="text-center text-muted py-3" id="no-waypoints-message">
                                                    <i class="bi bi-map display-6 d-block mb-2"></i>
                                                    No stops added yet. Click "Add Stop" or right-click on the map to add stops.
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary w-100" id="add-waypoint">
                                                <i class="bi bi-plus-circle me-2"></i> Add Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Map -->
                            <div class="col-lg-6">
                                <div class="sticky-top" style="top: 20px;">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Route Map <span class="text-danger">*</span></label>
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">Interactive Map</span>
                                                    <div class="map-legend">
                                                        <small class="me-3">
                                                            <span class="legend-dot bg-success"></span> Start
                                                        </small>
                                                        <small class="me-3">
                                                            <span class="legend-dot bg-danger"></span> End
                                                        </small>
                                                        <small>
                                                            <span class="legend-dot bg-primary"></span> Stops
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="alert alert-info border-0 rounded-0 m-0">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Instructions:</strong> Click on the map to set start and end points. Right-click to add stops.
                                                </div>

                                                <div id="route-map" style="height: 500px; width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Map Controls -->
                                    <div class="card border mt-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Quick Actions</h6>
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-success" id="set-start-point">
                                                    <i class="bi bi-geo-alt-fill me-2"></i> Set Start Point
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" id="set-end-point">
                                                    <i class="bi bi-geo-alt-fill me-2"></i> Set End Point
                                                </button>
                                                <button type="button" class="btn btn-outline-warning" id="clear-route">
                                                    <i class="bi bi-arrow-clockwise me-2"></i> Clear Route
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Back to Routes
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="submit-btn">
                                            <i class="bi bi-plus-circle me-1"></i> Create Route
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- COMPLETE UPDATED @push('styles') SECTION -->
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
/* Map Container */
#route-map {
    width: 100%;
    height: 500px;
    border-radius: 0 0 8px 8px;
    position: relative;
    overflow: hidden;
}

/* Leaflet Container */
.leaflet-container {
    height: 100% !important;
    width: 100% !important;
    position: relative !important;
    background: #eef2f6;
}

/* Fix for scroll and overflow */
.card-body.p-0 {
    overflow: hidden;
    position: relative;
}

/* Column positioning */
.col-lg-6 {
    position: relative;
}

/* Sticky sidebar */
.sticky-top {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    z-index: 10;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

/* Hide routing instructions panel */
.leaflet-routing-container {
    display: none !important;
}

/* Leaflet controls styling */
.leaflet-control-container {
    position: relative;
    z-index: 800;
}

.leaflet-control {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.leaflet-bar a {
    border-bottom: 1px solid #eee;
    width: 36px;
    height: 36px;
    line-height: 36px;
    text-align: center;
    font-size: 18px;
}

.leaflet-bar a:hover {
    background-color: #f8f9fc;
}

/* Leaflet positioning */
.leaflet-top {
    top: 15px;
}

.leaflet-left {
    left: 15px;
}

.leaflet-right {
    right: 15px;
}

.leaflet-bottom {
    bottom: 15px;
}

/* Popup styling */
.leaflet-popup-content {
    margin: 10px;
    font-size: 14px;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
}

/* Tooltip styling */
.leaflet-tooltip {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    padding: 5px 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Waypoint item styles */
.waypoint-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 10px;
    position: relative;
}

.waypoint-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.waypoint-actions {
    display: flex;
    gap: 5px;
}

.waypoint-number {
    background: #007bff;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    margin-right: 8px;
}

/* Legend styles */
.legend-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 4px;
}

.map-legend {
    display: flex;
    align-items: center;
}

/* Custom marker styles */
.custom-marker {
    background: transparent;
    border: none;
}

.marker-content {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    position: relative;
}

.start-marker .marker-content {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.end-marker .marker-content {
    background: linear-gradient(135deg, #dc3545, #c0392b);
}

.waypoint-marker .marker-content {
    background: linear-gradient(135deg, #007bff, #0056b3);
    width: 28px;
    height: 28px;
    font-size: 12px;
}

/* Autocomplete suggestions */
.autocomplete-suggestions {
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    position: absolute;
    width: 100%;
    margin-top: 2px;
}

.autocomplete-suggestion {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.autocomplete-suggestion:last-child {
    border-bottom: none;
}

.autocomplete-suggestion:hover,
.autocomplete-suggestion.bg-light {
    background-color: #f5f5f5;
}

.autocomplete-loading {
    padding: 8px 12px;
    color: #6c757d;
    font-style: italic;
}

.position-relative {
    position: relative;
}

/* Fix z-index layers */
.leaflet-pane {
    z-index: 400;
}

.leaflet-tile-pane {
    z-index: 200;
}

.leaflet-overlay-pane {
    z-index: 400;
}

.leaflet-shadow-pane {
    z-index: 500;
}

.leaflet-marker-pane {
    z-index: 600;
}

.leaflet-tooltip-pane {
    z-index: 650;
}

.leaflet-popup-pane {
    z-index: 700;
}

/* Bootstrap fix */
.row {
    display: flex;
    flex-wrap: wrap;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
}

.card-body {
    flex: 1 1 auto;
}
</style>
@endpush

<!-- COMPLETE UPDATED @push('scripts') SECTION -->
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
const OPENCAGE_API_KEY = '5246506e7d3141cbaaab53d198f6de47'; // Your OpenCage API key
let map, startMarker, endMarker, routingControl;
let waypointMarkers = [];
let waypointCounter = 0;

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initializeMap, 300);
});

function initializeMap() {
    const mapContainer = document.getElementById('route-map');
    if (!mapContainer) {
        console.error('Map container not found!');
        return;
    }

    if (mapContainer._leaflet_id) {
        mapContainer._leaflet_id = null;
    }

    try {
        // Initialize map centered on Davao, Philippines
        map = L.map('route-map', {
            scrollWheelZoom: true,
            touchZoom: true,
            zoomControl: true,
            dragging: true,
            tap: true,
            maxZoom: 18,
            minZoom: 3
        }).setView([7.0731, 125.6128], 13);

        // Add tile layers
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        });

        const cartoLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap © CARTO'
        });

        const baseMaps = {
            "OpenStreetMap": osmLayer,
            "CartoDB": cartoLayer
        };

        L.control.layers(baseMaps).addTo(map);
        osmLayer.addTo(map);

        // Add scale control
        L.control.scale({ imperial: false, metric: true, position: 'bottomright' }).addTo(map);

        // Force map to render properly
        setTimeout(() => map.invalidateSize(true), 400);

        // Try to get user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    map.setView([userLat, userLng], 13);
                },
                (error) => console.log('Geolocation error:', error)
            );
        }

        // Create custom marker icons
        const startIcon = L.divIcon({
            className: 'custom-marker start-marker',
            html: '<div class="marker-content">S</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        const endIcon = L.divIcon({
            className: 'custom-marker end-marker',
            html: '<div class="marker-content">E</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Initialize markers (hidden initially)
        const defaultCenter = [7.0731, 125.6128];
        startMarker = L.marker(defaultCenter, {
            draggable: true,
            icon: startIcon,
            opacity: 0
        }).addTo(map);

        endMarker = L.marker(defaultCenter, {
            draggable: true,
            icon: endIcon,
            opacity: 0
        }).addTo(map);

        // Map click event
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (startMarker.options.opacity === 0) {
                placeStartMarker(lat, lng);
            } else if (endMarker.options.opacity === 0) {
                placeEndMarker(lat, lng);
            }
        });

        // Map right-click for waypoints
        map.on('contextmenu', function(e) {
            if (startMarker.options.opacity > 0 && endMarker.options.opacity > 0) {
                addWaypointMarker(e.latlng.lat, e.latlng.lng);
            } else {
                showAlert('Please set start and end points first.', 'warning');
            }
            e.originalEvent.preventDefault();
        });

        // Marker drag events
        startMarker.on('dragend', function() {
            updatePositionFromMarker('start', startMarker);
            calculateRoute();
        });

        endMarker.on('dragend', function() {
            updatePositionFromMarker('end', endMarker);
            calculateRoute();
        });

        // Initialize routing control
        routingControl = L.Routing.control({
            waypoints: [],
            routeWhileDragging: false,
            showAlternatives: false,
            addWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: {
                styles: [{ color: '#007bff', weight: 6, opacity: 0.8 }]
            },
            createMarker: () => null,
            show: false
        }).addTo(map);

        // Setup autocomplete for address fields
        setupAutocomplete('start_point', 'start');
        setupAutocomplete('end_point', 'end');

        // Button event handlers
        document.getElementById('set-start-point').addEventListener('click', () => {
            showAlert('Click on the map to set the start point.', 'info');
        });

        document.getElementById('set-end-point').addEventListener('click', () => {
            showAlert('Click on the map to set the end point.', 'info');
        });

        document.getElementById('clear-route').addEventListener('click', clearRoute);
        document.getElementById('add-waypoint').addEventListener('click', () => {
            if (startMarker.options.opacity > 0 && endMarker.options.opacity > 0) {
                const center = map.getCenter();
                addWaypointMarker(center.lat, center.lng);
            } else {
                showAlert('Please set start and end points first.', 'warning');
            }
        });

        // Form submission validation
        document.getElementById('routeForm').addEventListener('submit', function(e) {
            if (startMarker.options.opacity === 0 || endMarker.options.opacity === 0) {
                e.preventDefault();
                showAlert('Please set both start and end points on the map.', 'danger');
                return false;
            }

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Creating Route...';
            submitBtn.disabled = true;
        });

        console.log('Map initialized successfully');

    } catch (error) {
        console.error('Error initializing map:', error);
        showAlert('Error loading map. Please refresh the page.', 'danger');
    }
}

// Place start marker
function placeStartMarker(lat, lng) {
    startMarker.setLatLng([lat, lng]).setOpacity(1);
    document.getElementById('start_latitude').value = lat;
    document.getElementById('start_longitude').value = lng;
    reverseGeocode({ lat, lng }, 'start_point');
    calculateRoute();
    showAlert('Start point set successfully!', 'success');
}

// Place end marker
function placeEndMarker(lat, lng) {
    endMarker.setLatLng([lat, lng]).setOpacity(1);
    document.getElementById('end_latitude').value = lat;
    document.getElementById('end_longitude').value = lng;
    reverseGeocode({ lat, lng }, 'end_point');
    calculateRoute();
    showAlert('End point set successfully!', 'success');
}

// Add waypoint marker
function addWaypointMarker(lat, lng) {
    waypointCounter++;
    const waypointId = 'waypoint_' + waypointCounter;
    const waypointName = `Stop ${waypointCounter}`;

    const waypointIcon = L.divIcon({
        className: 'custom-marker waypoint-marker',
        html: `<div class="marker-content">${waypointCounter}</div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });

    const marker = L.marker([lat, lng], {
        icon: waypointIcon,
        draggable: true
    }).addTo(map).bindPopup(`<strong>Stop ${waypointCounter}</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);

    marker.on('dragend', function() {
        const newLatLng = marker.getLatLng();
        const wp = waypointMarkers.find(w => w.id === waypointId);
        if (wp) {
            wp.lat = newLatLng.lat;
            wp.lng = newLatLng.lng;
            document.querySelector(`#${waypointId} input[name$="[latitude]"]`).value = newLatLng.lat;
            document.querySelector(`#${waypointId} input[name$="[longitude]"]`).value = newLatLng.lng;
            calculateRoute();
        }
    });

    waypointMarkers.push({ id: waypointId, marker, lat, lng });
    addWaypointToForm(waypointId, waypointName, lat, lng, waypointCounter);
    calculateRoute();
    showAlert(`Stop ${waypointCounter} added successfully!`, 'success');
}

// Add waypoint to form
function addWaypointToForm(id, name, lat, lng, sequence) {
    const container = document.getElementById('waypoints-container');
    const noWaypointsMessage = document.getElementById('no-waypoints-message');

    if (noWaypointsMessage) {
        noWaypointsMessage.style.display = 'none';
    }

    const waypointHtml = `
        <div class="waypoint-item" id="${id}">
            <div class="waypoint-header">
                <div class="d-flex align-items-center">
                    <div class="waypoint-number">${sequence}</div>
                    <strong>${name}</strong>
                </div>
                <div class="waypoint-actions">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editWaypoint('${id}')" title="Edit Stop">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeWaypoint('${id}')" title="Remove Stop">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">Stop Name</label>
                    <input type="text" class="form-control form-control-sm"
                           name="waypoints[${sequence}][name]" value="${name}"
                           placeholder="Enter stop name" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Latitude</label>
                    <input type="number" class="form-control form-control-sm"
                           name="waypoints[${sequence}][latitude]" value="${lat}"
                           step="any" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Longitude</label>
                    <input type="number" class="form-control form-control-sm"
                           name="waypoints[${sequence}][longitude]" value="${lng}"
                           step="any" readonly>
                </div>
            </div>
            <input type="hidden" name="waypoints[${sequence}][sequence]" value="${sequence}">
        </div>
    `;
    container.insertAdjacentHTML('beforeend', waypointHtml);
}

// Calculate route
function calculateRoute() {
    if (routingControl) {
        map.removeControl(routingControl);
    }

    if (startMarker.options.opacity > 0 && endMarker.options.opacity > 0) {
        const waypoints = waypointMarkers.map(wp => L.latLng(wp.lat, wp.lng));
        const routeWaypoints = [
            startMarker.getLatLng(),
            ...waypoints,
            endMarker.getLatLng()
        ];

        routingControl = L.Routing.control({
            waypoints: routeWaypoints,
            routeWhileDragging: false,
            showAlternatives: false,
            addWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: {
                styles: [{ color: '#007bff', weight: 6, opacity: 0.8 }]
            },
            createMarker: () => null,
            show: false
        }).addTo(map);

        routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            if (routes && routes[0]) {
                const route = routes[0];
                const distanceKm = (route.summary.totalDistance / 1000).toFixed(2);
                const durationMinutes = Math.round(route.summary.totalTime / 60);

                document.getElementById('distance').value = distanceKm;
                document.getElementById('estimated_duration').value = durationMinutes;

                setTimeout(() => {
                    const bounds = L.latLngBounds(routeWaypoints);
                    map.fitBounds(bounds, { padding: [50, 50] });
                }, 100);
            }
        });
    }
}

// Clear route
function clearRoute() {
    startMarker.setLatLng([7.0731, 125.6128]).setOpacity(0);
    endMarker.setLatLng([7.0731, 125.6128]).setOpacity(0);

    waypointMarkers.forEach(waypoint => map.removeLayer(waypoint.marker));
    waypointMarkers = [];
    waypointCounter = 0;

    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    document.getElementById('start_point').value = '';
    document.getElementById('start_latitude').value = '';
    document.getElementById('start_longitude').value = '';
    document.getElementById('end_point').value = '';
    document.getElementById('end_latitude').value = '';
    document.getElementById('end_longitude').value = '';
    document.getElementById('distance').value = '';
    document.getElementById('estimated_duration').value = '';

    const container = document.getElementById('waypoints-container');
    container.innerHTML = '<div class="text-center text-muted py-3" id="no-waypoints-message"><i class="bi bi-map display-6 d-block mb-2"></i>No stops added yet. Click "Add Stop" or right-click on the map to add stops.</div>';

    showAlert('Route cleared. You can start over.', 'info');
}

// Update position from marker
function updatePositionFromMarker(type, marker) {
    const latLng = marker.getLatLng();
    document.getElementById(`${type}_latitude`).value = latLng.lat;
    document.getElementById(`${type}_longitude`).value = latLng.lng;
    reverseGeocode(latLng, `${type}_point`);
}

// Reverse geocode
function reverseGeocode(latLng, inputId) {
    const url = `https://api.opencagedata.com/geocode/v1/json?q=${latLng.lat}+${latLng.lng}&key=${OPENCAGE_API_KEY}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.results && data.results[0]) {
                document.getElementById(inputId).value = data.results[0].formatted;
            }
        })
        .catch(error => console.error('Reverse geocoding error:', error));
}

// Setup autocomplete
function setupAutocomplete(inputId, type) {
    const input = document.getElementById(inputId);
    const container = document.createElement('div');
    container.className = 'position-relative';
    input.parentNode.insertBefore(container, input);
    container.appendChild(input);

    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'autocomplete-suggestions';
    suggestionsContainer.style.display = 'none';
    container.appendChild(suggestionsContainer);

    let currentController = null;
    let selectedIndex = -1;
    let suggestions = [];

    input.addEventListener('input', function() {
        const query = input.value.trim();

        if (currentController) {
            currentController.abort();
            currentController = null;
        }

        if (query.length < 3) {
            hideSuggestions();
            return;
        }

        suggestionsContainer.innerHTML = '<div class="autocomplete-loading">Searching...</div>';
        showSuggestions();

        currentController = new AbortController();
        const url = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(query)}&key=${OPENCAGE_API_KEY}&limit=5&countrycode=ph`;

        fetch(url, { signal: currentController.signal })
            .then(response => response.json())
            .then(data => {
                suggestionsContainer.innerHTML = '';
                suggestions = data.results || [];

                if (suggestions.length === 0) {
                    suggestionsContainer.innerHTML = '<div class="autocomplete-loading">No results found</div>';
                    return;
                }

                suggestions.forEach((result, index) => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'autocomplete-suggestion';
                    suggestion.textContent = result.formatted;
                    suggestion.dataset.index = index;

                    suggestion.addEventListener('click', () => selectSuggestion(index));
                    suggestionsContainer.appendChild(suggestion);
                });
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Geocoding error:', error);
                    suggestionsContainer.innerHTML = '<div class="autocomplete-loading">Error fetching locations</div>';
                }
            });
    });

    function selectSuggestion(index) {
        if (index >= 0 && index < suggestions.length) {
            const result = suggestions[index];
            input.value = result.formatted;
            hideSuggestions();

            document.getElementById(`${type}_latitude`).value = result.geometry.lat;
            document.getElementById(`${type}_longitude`).value = result.geometry.lng;

            const marker = type === 'start' ? startMarker : endMarker;
            marker.setLatLng([result.geometry.lat, result.geometry.lng]).setOpacity(1);
            map.panTo([result.geometry.lat, result.geometry.lng]);

            calculateRoute();
        }
    }

    function showSuggestions() {
        suggestionsContainer.style.display = 'block';
    }

    function hideSuggestions() {
        suggestionsContainer.style.display = 'none';
        selectedIndex = -1;
    }

    input.addEventListener('keydown', function(e) {
        if (suggestionsContainer.style.display === 'none') return;

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % suggestions.length;
                highlightSuggestion();
                break;
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
                highlightSuggestion();
                break;
            case 'Enter':
                e.preventDefault();
                selectSuggestion(selectedIndex);
                break;
            case 'Escape':
                e.preventDefault();
                hideSuggestions();
                break;
        }
    });

    function highlightSuggestion() {
        const allSuggestions = suggestionsContainer.querySelectorAll('.autocomplete-suggestion');
        allSuggestions.forEach((s, i) => {
            s.classList.toggle('bg-light', i === selectedIndex);
        });
    }

    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) hideSuggestions();
    });
}

// Edit waypoint
window.editWaypoint = function(id) {
    const waypoint = waypointMarkers.find(wp => wp.id === id);
    if (waypoint) {
        const currentName = document.querySelector(`#${id} input[name$="[name]"]`).value;
        const newName = prompt('Enter new name for this stop:', currentName);
        if (newName && newName.trim() !== '') {
            document.querySelector(`#${id} input[name$="[name]"]`).value = newName.trim();
            document.querySelector(`#${id} .waypoint-header strong`).textContent = newName.trim();
            waypoint.marker.bindPopup(`<strong>${newName.trim()}</strong><br>Lat: ${waypoint.lat.toFixed(6)}<br>Lng: ${waypoint.lng.toFixed(6)}`);
            showAlert('Stop name updated!', 'success');
        }
    }
};

// Remove waypoint
window.removeWaypoint = function(id) {
    const waypointIndex = waypointMarkers.findIndex(wp => wp.id === id);
    if (waypointIndex !== -1) {
        const waypoint = waypointMarkers[waypointIndex];
        map.removeLayer(waypoint.marker);
        waypointMarkers.splice(waypointIndex, 1);
        document.getElementById(id).remove();
        calculateRoute();
        renumberWaypoints();
        showAlert('Stop removed!', 'warning');

        if (waypointMarkers.length === 0) {
            const container = document.getElementById('waypoints-container');
            container.innerHTML = '<div class="text-center text-muted py-3" id="no-waypoints-message"><i class="bi bi-map display-6 d-block mb-2"></i>No stops added yet. Click "Add Stop" or right-click on the map to add stops.</div>';
        }
    }
};

// Renumber waypoints
function renumberWaypoints() {
    const waypointItems = document.querySelectorAll('.waypoint-item');
    waypointItems.forEach((item, index) => {
        const sequence = index + 1;
        const header = item.querySelector('.waypoint-header strong');
        const numberDiv = item.querySelector('.waypoint-number');

        header.textContent = `Stop ${sequence}`;
        numberDiv.textContent = sequence;

        const sequenceInput = item.querySelector('input[name$="[sequence]"]');
        sequenceInput.value = sequence;

        const inputs = item.querySelectorAll('input[name*="waypoints"]');
        inputs.forEach(input => {
            const name = input.name.replace(/waypoints\[\d+\]/, `waypoints[${sequence}]`);
            input.name = name;
        });

        const waypointId = item.id;
        const waypoint = waypointMarkers.find(wp => wp.id === waypointId);
        if (waypoint) {
            const waypointIcon = L.divIcon({
                className: 'custom-marker waypoint-marker',
                html: `<div class="marker-content">${sequence}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 28],
                popupAnchor: [0, -28]
            });
            waypoint.marker.setIcon(waypointIcon);
        }
    });
}

// Show alert notification
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>
@endpush
