@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header with KPIs & Filters -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="mb-1 text-heading">Dashboard Overview</h4>
            <p class="text-secondary-body mb-0 small">Welcome back! Here's what's happening with your fleet today.</p>
        </div>

    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Active Buses -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                            <i class="bi bi-bus-front"></i>
                        </div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                            <i class="bi bi-arrow-up-right me-1"></i>5%
                        </span>
                    </div>
                    <div class="text-secondary-body small mb-1">Active Buses</div>
                    <div class="h3 mb-2 text-heading">12</div>
                    <div class="progress" style="height: 6px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 72%"></div>
                    </div>
                    <div class="small text-secondary-body mt-1">8 of 12 buses on route</div>
                </div>
            </div>
        </div>

        <!-- Staff -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                            <i class="bi bi-people"></i>
                        </div>
                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-dash me-1"></i>0%
                        </span>
                    </div>
                    <div class="text-secondary-body small mb-1">Registered Staff</div>
                    <div class="h3 mb-2 text-heading">35</div>
                    <div class="progress" style="height: 6px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 50%"></div>
                    </div>
                    <div class="small text-secondary-body mt-1">28 staff active today</div>
                </div>
            </div>
        </div>

        <!-- Trips Today -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm  kpi-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-icon" style="background: rgba(45, 156, 110, 0.1); color: #2D9C6E;">
                            <i class="bi bi-map"></i>
                        </div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                            <i class="bi bi-arrow-up-right me-1"></i>12%
                        </span>
                    </div>
                    <div class="text-secondary-body small mb-1">Trips Today</div>
                    <div class="h3 mb-2 text-heading">58</div>
                    <div class="progress" style="height: 6px">
                        <div class="progress-bar" role="progressbar" style="width: 68%; background: #2D9C6E;"></div>
                    </div>
                    <div class="small text-secondary-body mt-1">42 trips completed</div>
                </div>
            </div>
        </div>

        <!-- Open Alerts -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm  kpi-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-arrow-down-right me-1"></i>-2
                        </span>
                    </div>
                    <div class="text-secondary-body small mb-1">Open Alerts</div>
                    <div class="h3 mb-2 text-heading">3</div>
                    <div class="progress" style="height: 6px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                    </div>
                    <div class="small text-secondary-body mt-1">2 resolved today</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Panels -->
    <div class="row g-3">
        <!-- Left Column -->
        <div class="col-xxl-8">
            <!-- Operations Chart -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="h6 mb-0">Operations Overview</div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-primary active">Trips</button>
                        <button class="btn btn-outline-primary">Passengers</button>
                        <button class="btn btn-outline-primary">Delays</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart Container -->
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="operationsChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <div class="h5 text-heading mb-1">42</div>
                            <div class="small text-secondary-body">Completed</div>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-heading mb-1">12</div>
                            <div class="small text-secondary-body">In Progress</div>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-heading mb-1">4</div>
                            <div class="small text-secondary-body">Delayed</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Trips -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="h6 mb-0">Recent Trips</div>
                    <a href="#" class="btn btn-sm btn-outline-primary">View all <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Bus</th>
                                <th>Route</th>
                                <th>Driver</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="fw-semibold">08:20</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bus-front text-primary me-2"></i>
                                        <span>YBL-1234</span>
                                    </div>
                                </td>
                                <td>YBL-23</td>
                                <td>Michael R.</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">On time</span></td>

                            </tr>
                            <tr>
                                <td><span class="fw-semibold">09:05</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bus-front text-warning me-2"></i>
                                        <span>YBL-5678</span>
                                    </div>
                                </td>
                                <td>YBL-11</td>
                                <td>Sarah L.</td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Delayed</span></td>

                            </tr>
                            <tr>
                                <td><span class="fw-semibold">09:30</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bus-front text-secondary me-2"></i>
                                        <span>YBL-7788</span>
                                    </div>
                                </td>
                                <td>YBL-05</td>
                                <td>Anna K.</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Arrived</span></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-xxl-4">
            <!-- Alerts & Notices -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="h6 mb-0">Alerts & Notices</div>
                    <span class="badge bg-danger">3</span>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning d-flex align-items-start" role="alert">
                        <i class="bi bi-cone-striped me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Roadworks Detour</div>
                            <div class="small">Roadworks near Station 3 — expect 5 min delay on Route YBL-11.</div>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class="bi bi-info-circle me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Weather Advisory</div>
                            <div class="small">Heavy rain expected — routes may experience delays.</div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold small">Maintenance Window</div>
                                <div class="small text-secondary-body">22:00–23:00 tonight</div>
                            </div>
                            <span class="badge bg-secondary">Today</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold small">Driver Training</div>
                                <div class="small text-secondary-body">New safety protocols</div>
                            </div>
                            <span class="badge bg-primary">Tomorrow</span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-secondary-body">Fleet Utilization</span>
                            <span class="small fw-semibold">76%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: 76%; background: var(--primary-yellow);"></div>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-secondary-body">On-time Performance</span>
                            <span class="small fw-semibold">89%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: 89%; background: #2D9C6E;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="h6 mb-0">Recent Activity</div>
                    <a href="#" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="activity-badge bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-semibold">Conductor John</div>
                                <div class="small text-secondary-body">Added a new drop-off point</div>
                                <div class="small text-muted">10 minutes ago</div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="activity-badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-semibold">Bus YBL-12</div>
                                <div class="small text-secondary-body">Started route YBL-23</div>
                                <div class="small text-muted">45 minutes ago</div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="activity-badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-semibold">Driver Anna</div>
                                <div class="small text-secondary-body">Confirmed arrival at Station 4</div>
                                <div class="small text-muted">1 hour ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .activity-badge {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .chart-container {
        position: relative;
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem 0.5rem;
    }

    .list-group-item {
        border-color: rgba(0, 0, 0, 0.05);
    }
</style>

<!-- Chart.js for the operations chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('operationsChart').getContext('2d');
        const operationsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['6:00', '8:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
                datasets: [{
                    label: 'Trips Completed',
                    data: [12, 19, 15, 25, 22, 30, 28],
                    backgroundColor: 'rgba(45, 156, 110, 0.7)',
                    borderColor: 'rgba(45, 156, 110, 1)',
                    borderWidth: 1
                }, {
                    label: 'Trips in Progress',
                    data: [5, 8, 10, 7, 12, 9, 6],
                    backgroundColor: 'rgba(255, 213, 79, 0.7)',
                    borderColor: 'rgba(255, 213, 79, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Trips'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time of Day'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
