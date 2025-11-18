@extends('layouts.admin')

@section('title', 'Edit Route')
@section('page_title', 'Edit Route')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-signpost me-2"></i>Edit Route - {{ $route->route_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.routes.update', $route) }}" method="POST" id="routeForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="route_name" class="form-label">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('route_name') is-invalid @enderror"
                                           id="route_name" name="route_name" value="{{ old('route_name', $route->route_name) }}"
                                           placeholder="e.g., Downtown Express" required>
                                    @error('route_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Route description...">{{ old('description', $route->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_point" class="form-label">Start Point <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('start_point') is-invalid @enderror"
                                                   id="start_point" name="start_point" value="{{ old('start_point', $route->start_point) }}"
                                                   placeholder="e.g., Central Station" required>
                                            @error('start_point')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" id="start_latitude" name="start_latitude" value="{{ old('start_latitude', $route->start_latitude) }}">
                                            <input type="hidden" id="start_longitude" name="start_longitude" value="{{ old('start_longitude', $route->start_longitude) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_point" class="form-label">End Point <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('end_point') is-invalid @enderror"
                                                   id="end_point" name="end_point" value="{{ old('end_point', $route->end_point) }}"
                                                   placeholder="e.g., City Mall" required>
                                            @error('end_point')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" id="end_latitude" name="end_latitude" value="{{ old('end_latitude', $route->end_latitude) }}">
                                            <input type="hidden" id="end_longitude" name="end_longitude" value="{{ old('end_longitude', $route->end_longitude) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="distance" class="form-label">Distance (km) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('distance') is-invalid @enderror"
                                                   id="distance" name="distance" value="{{ old('distance', $route->distance) }}"
                                                   step="0.01" min="0.01" required>
                                            @error('distance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="estimated_duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror"
                                                   id="estimated_duration" name="estimated_duration" value="{{ old('estimated_duration', $route->estimated_duration) }}"
                                                   min="1" required>
                                            @error('estimated_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Waypoints Section -->
                                <div class="mb-3">
                                    <label class="form-label">Waypoints (Intermediate Stops)</label>
                                    <div id="waypoints-container">
                                        <!-- Waypoints will be loaded from existing data -->
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-waypoint">
                                        <i class="bi bi-plus me-1"></i> Add Stop
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Route Map <span class="text-danger">*</span></label>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Click on the map to set start and end points. Right-click to add waypoints.
                                    </div>
                                    <div id="route-map" style="height: 500px; border-radius: 8px;"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Routes
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Update Route
                                    </button>
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
<!-- COMPLETE FIXED EDIT ROUTE @push('styles') -->
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
/* Map Container */
#route-map {
    width: 100%;
    height: 500px;
    border-radius: 8px;
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

/* Hide routing instructions panel */
.leaflet-routing-container {
    display: none !important;
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

/* Leaflet controls styling */
.leaflet-control {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.leaflet-bar a {
    border-bottom: 1px solid #eee;
    width: 36px;
    height: 36px;
    line-height: 36px;
}

.leaflet-bar a:hover {
    background-color: #f8f9fc;
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
</style>
@endpush

<!-- COMPLETE FIXED EDIT ROUTE @push('scripts') -->
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
const OPENCAGE_API_KEY = '5246506e7d3141cbaaab53d198f6de47';
let map, startMarker, endMarker, routingControl;
let waypointMarkers = [];
let waypointCounter = 0;

// Existing route data from server
const existingRoute = {
    start_lat: parseFloat({{ $route->start_latitude }}),
    start_lng: parseFloat({{ $route->start_longitude }}),
    start_point: "{{ $route->start_point }}",
    end_lat: parseFloat({{ $route->end_latitude }}),
    end_lng: parseFloat({{ $route->end_longitude }}),
    end_point: "{{ $route->end_point }}",
    waypoints: @json($route->waypoints ? json_decode($route->waypoints, true) : [])
};


console.log(existingRoute);

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
        // Initialize map centered on existing route
        map = L.map('route-map', {
            scrollWheelZoom: true,
            touchZoom: true,
            zoomControl: true,
            dragging: true,
            tap: true,
            maxZoom: 18,
            minZoom: 3
        }).setView([existingRoute.start_lat, existingRoute.start_lng], 13);

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

        L.control.scale({ imperial: false, metric: true, position: 'bottomright' }).addTo(map);

        setTimeout(() => map.invalidateSize(true), 400);

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

        // Set existing start marker
        startMarker = L.marker([existingRoute.start_lat, existingRoute.start_lng], {
            draggable: true,
            icon: startIcon
        }).addTo(map).bindPopup(`<strong>Start Point</strong><br>${existingRoute.start_point}`);

        // Set existing end marker
        endMarker = L.marker([existingRoute.end_lat, existingRoute.end_lng], {
            draggable: true,
            icon: endIcon
        }).addTo(map).bindPopup(`<strong>End Point</strong><br>${existingRoute.end_point}`);

        // Load existing waypoints
        if (existingRoute.waypoints && existingRoute.waypoints.length > 0) {
            existingRoute.waypoints.forEach((waypoint, index) => {
                waypointCounter = Math.max(waypointCounter, waypoint.sequence || index + 1);
                addWaypointMarker(
                    waypoint.latitude,
                    waypoint.longitude,
                    waypoint.name,
                    waypoint.sequence || index + 1
                );
            });
        }

        // Calculate initial route
        calculateRoute();

        // Map click event
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            placeStartMarker(lat, lng);
        });

        // Map right-click for waypoints
        map.on('contextmenu', function(e) {
            if (startMarker && endMarker) {
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

        // Setup autocomplete
        setupAutocomplete('start_point', 'start');
        setupAutocomplete('end_point', 'end');

        // Button event handlers
        document.getElementById('add-waypoint').addEventListener('click', () => {
            if (startMarker && endMarker) {
                const center = map.getCenter();
                addWaypointMarker(center.lat, center.lng);
            } else {
                showAlert('Please set start and end points first.', 'warning');
            }
        });

        // Form submission validation
        document.getElementById('routeForm').addEventListener('submit', function(e) {
            if (!startMarker || !endMarker) {
                e.preventDefault();
                showAlert('Please set both start and end points on the map.', 'danger');
                return false;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Updating Route...';
            submitBtn.disabled = true;
        });

        console.log('Map initialized successfully with existing route data');

    } catch (error) {
        console.error('Error initializing map:', error);
        showAlert('Error loading map. Please refresh the page.', 'danger');
    }
}

// Place start marker
function placeStartMarker(lat, lng) {
    startMarker.setLatLng([lat, lng]);
    document.getElementById('start_latitude').value = lat;
    document.getElementById('start_longitude').value = lng;
    reverseGeocode({ lat, lng }, 'start_point');
    calculateRoute();
    showAlert('Start point updated!', 'success');
}

// Place end marker
function placeEndMarker(lat, lng) {
    endMarker.setLatLng([lat, lng]);
    document.getElementById('end_latitude').value = lat;
    document.getElementById('end_longitude').value = lng;
    reverseGeocode({ lat, lng }, 'end_point');
    calculateRoute();
    showAlert('End point updated!', 'success');
}

// Add waypoint marker
function addWaypointMarker(lat, lng, name = null, sequence = null) {
    if (!sequence) {
        waypointCounter++;
        sequence = waypointCounter;
    }

    // Ensure lat and lng are numbers
    const numLat = typeof lat === 'string' ? parseFloat(lat) : lat;
    const numLng = typeof lng === 'string' ? parseFloat(lng) : lng;

    const waypointId = 'waypoint_' + sequence;
    const waypointName = name || `Stop ${sequence}`;

    const waypointIcon = L.divIcon({
        className: 'custom-marker waypoint-marker',
        html: `<div class="marker-content">${sequence}</div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });

    const marker = L.marker([numLat, numLng], {
        icon: waypointIcon,
        draggable: true
    }).addTo(map).bindPopup(`<strong>${waypointName}</strong><br>Lat: ${numLat.toFixed(6)}<br>Lng: ${numLng.toFixed(6)}`);

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

    waypointMarkers.push({ id: waypointId, marker, lat: numLat, lng: numLng, sequence });
    addWaypointToForm(waypointId, waypointName, numLat, numLng, sequence);
    calculateRoute();

    if (!name) {
        showAlert(`Stop ${sequence} added successfully!`, 'success');
    }
}
// Add waypoint to form
function addWaypointToForm(id, name, lat, lng, sequence) {
    const container = document.getElementById('waypoints-container');

    // Check if waypoint already exists (for loading existing data)
    if (document.getElementById(id)) {
        return;
    }

    // Ensure numeric values for display
    const numLat = typeof lat === 'string' ? parseFloat(lat) : lat;
    const numLng = typeof lng === 'string' ? parseFloat(lng) : lng;

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
                           name="waypoints[${sequence}][latitude]" value="${numLat}"
                           step="any" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Longitude</label>
                    <input type="number" class="form-control form-control-sm"
                           name="waypoints[${sequence}][longitude]" value="${numLng}"
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

    if (startMarker && endMarker) {
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
            marker.setLatLng([result.geometry.lat, result.geometry.lng]);
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
            waypoint.sequence = sequence;
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
