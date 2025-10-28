@extends('layouts.admin')

@section('title', 'Tracking')
@section('page_title', 'Tracking')

@section('content')
    <div class="container-fluid">
        <!-- Map Controls -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0">Live Bus Tracking</h6>
                                <span class="badge bg-success">Real-time</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-filter me-1"></i>Filter Buses
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-filter="all">All Buses</a></li>
                                        <li><a class="dropdown-item" href="#" data-filter="active">Active Only</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#" data-filter="delayed">Delayed</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#" data-filter="route">By Route</a></li>
                                    </ul>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" id="refreshMap">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                                </button>
                                <button class="btn btn-primary btn-sm" id="centerMap">
                                    <i class="bi bi-geo-alt me-1"></i>Center Map
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Map Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div id="trackingMap" style="height: 600px; border-radius: 0.375rem;"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Active Buses List -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <div class="h6 mb-0">Active Buses <span class="badge bg-primary" id="activeBusCount">5</span></div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="busList">
                            <!-- Bus items will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Route Information -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="h6 mb-0">Route Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small text-secondary-body">Select Route</label>
                            <select class="form-select" id="routeSelector">
                                <option value="all">All Routes</option>
                                <option value="YBL-23">YBL-23 (Downtown Loop)</option>
                                <option value="YBL-11">YBL-11 (North Express)</option>
                                <option value="YBL-05">YBL-05 (West Corridor)</option>
                                <option value="YBL-17">YBL-17 (East Connector)</option>
                            </select>
                        </div>
                        <div id="routeDetails">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Select a route to view details
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #trackingMap {
            z-index: 1;
        }

        .bus-marker {
            background: white;
            border: 3px solid;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        .bus-marker.on-time {
            border-color: #10B981;
        }

        .bus-marker.delayed {
            border-color: #EF4444;
        }

        .bus-marker.early {
            border-color: #3B82F6;
        }

        .bus-list-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .bus-list-item:hover {
            background-color: #f8f9fa;
        }

        .bus-list-item.active {
            background-color: #e3f2fd;
            border-left: 3px solid #3B82F6;
        }

        .leaflet-popup-content {
            margin: 12px 16px;
            min-width: 200px;
        }

        .route-path {
            stroke-width: 4;
            stroke-opacity: 0.7;
            fill: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            // Initialize map centered in Mindanao (Davao City area)
            const map = L.map('trackingMap').setView([7.1907, 125.4553], 10); // Davao City coordinates

            // Add tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Sample bus data - in real app, this would come from your backend
            const buses = [{
                    id: 1,
                    busNumber: 'YBL-1234',
                    route: 'YBL-23',
                    driver: 'Michael R.',
                    status: 'on-time',
                    lat: 7.1907,
                    lng: 125.4553,
                    speed: 32,
                    lastUpdate: '2 min ago',
                    passengers: 24,
                    capacity: 40
                },
                {
                    id: 2,
                    busNumber: 'YBL-5678',
                    route: 'YBL-11',
                    driver: 'Sarah L.',
                    status: 'delayed',
                    lat: 7.2040,
                    lng: 125.4300,
                    speed: 25,
                    lastUpdate: '1 min ago',
                    passengers: 18,
                    capacity: 40
                },
                {
                    id: 3,
                    busNumber: 'YBL-7788',
                    route: 'YBL-05',
                    driver: 'Anna K.',
                    status: 'on-time',
                    lat: 7.2500,
                    lng: 125.5000,
                    speed: 28,
                    lastUpdate: '3 min ago',
                    passengers: 32,
                    capacity: 40
                },
                {
                    id: 4,
                    busNumber: 'YBL-9911',
                    route: 'YBL-17',
                    driver: 'James P.',
                    status: 'early',
                    lat: 7.2700,
                    lng: 125.4800,
                    speed: 35,
                    lastUpdate: 'Just now',
                    passengers: 15,
                    capacity: 40
                },
                {
                    id: 5,
                    busNumber: 'YBL-4455',
                    route: 'YBL-23',
                    driver: 'Maria S.',
                    status: 'on-time',
                    lat: 7.1800,
                    lng: 125.4600,
                    speed: 30,
                    lastUpdate: '4 min ago',
                    passengers: 28,
                    capacity: 40
                }
            ];

            // Sample route paths (Mindanao region)
            const routes = {
                'YBL-23': [
                    [7.2700, 125.4800],
                    [7.2400, 125.4700],
                    [7.2100, 125.4600],
                    [7.1907, 125.4553],
                    [7.1700, 125.4500]
                ],
                'YBL-11': [
                    [7.2100, 125.5000],
                    [7.2000, 125.4800],
                    [7.1907, 125.4553],
                    [7.1800, 125.4400],
                    [7.1700, 125.4200]
                ],
                'YBL-05': [
                    [7.2500, 125.5000],
                    [7.2300, 125.4800],
                    [7.2100, 125.4600],
                    [7.1907, 125.4553],
                    [7.1700, 125.4400]
                ],
                'YBL-17': [
                    [7.2700, 125.4800],
                    [7.2500, 125.4700],
                    [7.2300, 125.4600],
                    [7.2100, 125.4553],
                    [7.1900, 125.4400]
                ]
            };

            // Store markers and route layers
            const busMarkers = {};
            const routeLayers = {};
            let currentRoute = 'all';

            // Function to create bus marker
            function createBusMarker(bus) {
                const marker = L.marker([bus.lat, bus.lng], {
                    icon: L.divIcon({
                        className: `bus-marker ${bus.status}`,
                        html: `<div>${bus.busNumber.slice(-2)}</div>`,
                        iconSize: [24, 24]
                    })
                });

                const popupContent = `
                <div class="bus-popup">
                    <h6 class="mb-2">${bus.busNumber}</h6>
                    <div class="row small g-2">
                        <div class="col-6"><strong>Route:</strong></div>
                        <div class="col-6">${bus.route}</div>
                        <div class="col-6"><strong>Driver:</strong></div>
                        <div class="col-6">${bus.driver}</div>
                        <div class="col-6"><strong>Status:</strong></div>
                        <div class="col-6">
                            <span class="badge ${getStatusBadgeClass(bus.status)}">
                                ${getStatusText(bus.status)}
                            </span>
                        </div>
                        <div class="col-6"><strong>Speed:</strong></div>
                        <div class="col-6">${bus.speed} mph</div>
                        <div class="col-6"><strong>Passengers:</strong></div>
                        <div class="col-6">${bus.passengers}/${bus.capacity}</div>
                        <div class="col-6"><strong>Last Update:</strong></div>
                        <div class="col-6">${bus.lastUpdate}</div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-primary w-100" onclick="focusOnBus(${bus.id})">
                            <i class="bi bi-eye me-1"></i>Focus
                        </button>
                    </div>
                </div>
            `;

                marker.bindPopup(popupContent);
                return marker;
            }

            // Function to create route path
            function createRoutePath(routeName, coordinates) {
                return L.polyline(coordinates, {
                    color: getRouteColor(routeName),
                    weight: 4,
                    opacity: 0.7,
                    className: 'route-path'
                });
            }

            // Helper functions
            function getStatusBadgeClass(status) {
                const classes = {
                    'on-time': 'bg-success',
                    'delayed': 'bg-danger',
                    'early': 'bg-primary'
                };
                return classes[status] || 'bg-secondary';
            }

            function getStatusText(status) {
                const texts = {
                    'on-time': 'On Time',
                    'delayed': 'Delayed',
                    'early': 'Early'
                };
                return texts[status] || 'Unknown';
            }

            function getRouteColor(routeName) {
                const colors = {
                    'YBL-23': '#10B981',
                    'YBL-11': '#3B82F6',
                    'YBL-05': '#EF4444',
                    'YBL-17': '#8B5CF6'
                };
                return colors[routeName] || '#6B7280';
            }

            // Initialize buses on map
            function initializeBuses() {
                buses.forEach(bus => {
                    const marker = createBusMarker(bus);
                    busMarkers[bus.id] = marker;
                    marker.addTo(map);
                });
            }

            // Initialize route paths
            function initializeRoutes() {
                Object.keys(routes).forEach(routeName => {
                    const routeLayer = createRoutePath(routeName, routes[routeName]);
                    routeLayers[routeName] = routeLayer;
                    // Don't add to map by default - will be added when selected
                });
            }

            // Update bus list in sidebar
            function updateBusList() {
                const busList = document.getElementById('busList');
                const activeBusCount = document.getElementById('activeBusCount');

                busList.innerHTML = '';
                activeBusCount.textContent = buses.length;

                buses.forEach(bus => {
                    const listItem = document.createElement('div');
                    listItem.className = 'list-group-item bus-list-item';
                    listItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">${bus.busNumber}</div>
                            <div class="small text-secondary-body">${bus.route} • ${bus.driver}</div>
                            <div class="small">
                                <span class="badge ${getStatusBadgeClass(bus.status)} me-1">
                                    ${getStatusText(bus.status)}
                                </span>
                                <span class="text-muted">${bus.lastUpdate}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">${bus.speed} mph</div>
                            <div class="small text-muted">${bus.passengers}/${bus.capacity}</div>
                        </div>
                    </div>
                `;

                    listItem.addEventListener('click', () => {
                        // Remove active class from all items
                        document.querySelectorAll('.bus-list-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        // Add active class to clicked item
                        listItem.classList.add('active');
                        // Focus on the bus
                        focusOnBus(bus.id);
                    });

                    busList.appendChild(listItem);
                });
            }

            // Function to focus on a specific bus
            window.focusOnBus = function(busId) {
                const bus = buses.find(b => b.id === busId);
                if (bus) {
                    map.setView([bus.lat, bus.lng], 15);
                    busMarkers[busId].openPopup();
                }
            };

            // Function to update route display
            function updateRouteDisplay(routeName) {
                // Remove all existing route layers
                Object.values(routeLayers).forEach(layer => {
                    if (map.hasLayer(layer)) {
                        map.removeLayer(layer);
                    }
                });

                // Add selected route layer
                if (routeName !== 'all' && routeLayers[routeName]) {
                    routeLayers[routeName].addTo(map);

                    // Update route details
                    const routeDetails = document.getElementById('routeDetails');
                    routeDetails.innerHTML = `
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">${routeName}</span>
                            <span class="badge" style="background: ${getRouteColor(routeName)}; color: white;">Active</span>
                        </div>
                        <div class="small text-secondary-body">
                            <div class="mb-1"><i class="bi bi-clock me-1"></i>Frequency: 15 minutes</div>
                            <div class="mb-1"><i class="bi bi-people me-1"></i>Average ridership: 85%</div>
                            <div><i class="bi bi-check-circle me-1"></i>On-time performance: 92%</div>
                        </div>
                    </div>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        ${getBusesOnRoute(routeName).length} buses currently active on this route
                    </div>
                `;
                } else {
                    // Show all routes or default message
                    const routeDetails = document.getElementById('routeDetails');
                    routeDetails.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        ${routeName === 'all' ? 'Showing all routes' : 'Select a route to view details'}
                    </div>
                `;
                }
            }

            // Helper function to get buses on a specific route
            function getBusesOnRoute(routeName) {
                return buses.filter(bus => bus.route === routeName);
            }

            // Event listeners
            document.getElementById('refreshMap').addEventListener('click', function() {
                // Simulate data refresh
                buses.forEach(bus => {
                    // Small random movement for demo
                    bus.lat += (Math.random() - 0.5) * 0.001;
                    bus.lng += (Math.random() - 0.5) * 0.001;

                    // Update marker position
                    if (busMarkers[bus.id]) {
                        busMarkers[bus.id].setLatLng([bus.lat, bus.lng]);
                    }
                });

                // Show refresh feedback
                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check me-1"></i>Refreshed';
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');

                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            });

            document.getElementById('centerMap').addEventListener('click', function() {
                map.setView([40.7128, -74.0060], 12);
            });

            document.getElementById('routeSelector').addEventListener('change', function() {
                currentRoute = this.value;
                updateRouteDisplay(currentRoute);
            });

            // Filter dropdown functionality
            document.querySelectorAll('.dropdown-item[data-filter]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.getAttribute('data-filter');
                    // Implement filtering logic here
                    console.log('Filter by:', filter);
                });
            });

            // Initialize everything
            initializeBuses();
            initializeRoutes();
            updateBusList();
            updateRouteDisplay('all');

            // Auto-refresh simulation (every 30 seconds)
            setInterval(() => {
                // In a real app, this would fetch new data from your API
                console.log('Auto-refreshing bus positions...');
            }, 30000);
        });
    </script>
@endsection
