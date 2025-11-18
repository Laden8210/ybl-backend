@extends('layouts.admin')

@section('title', 'Route Management')
@section('page_title', 'Route Management')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h5 mb-0 text-gray-800">Bus Routes</h2>
            <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Create Route
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">#</th>
                                <th>Route Name</th>
                                <th>Start Point</th>
                                <th>End Point</th>
                                <th>Distance</th>
                                <th>Duration</th>
                                <th>Waypoints</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $index => $route)
                                <tr>
                                    <td class="px-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="px-2 py-1 avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="bi bi-signpost text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $route->route_name }}</h6>
                                                @if ($route->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($route->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                                            <div>
                                                <small class="fw-bold">{{ $route->start_point }}</small>
                                                <br>
                                                <small class="text-muted">
                                                    {{ number_format($route->start_latitude, 6) }},
                                                    {{ number_format($route->start_longitude, 6) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                            <div>
                                                <small class="fw-bold">{{ $route->end_point }}</small>
                                                <br>
                                                <small class="text-muted">
                                                    {{ number_format($route->end_latitude, 6) }},
                                                    {{ number_format($route->end_longitude, 6) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="bi bi-arrow-left-right me-1"></i>{{ $route->formatted_distance }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>{{ $route->formatted_duration }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            // Safely decode waypoints and count them
                                            $waypoints = $route->waypoints ? json_decode($route->waypoints, true) : [];
                                            $waypointCount = is_array($waypoints) ? count($waypoints) : 0;
                                        @endphp

                                        @if ($waypointCount > 0)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-map me-1"></i>{{ $waypointCount }} stops
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">No stops</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $route->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $route->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.routes.show', $route) }}"
                                                class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.routes.edit', $route) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit Route">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('admin.routes.toggle-status', $route) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $route->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $route->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi {{ $route->is_active ? 'bi-pause' : 'bi-play' }}"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-outline-danger delete-route"
                                                data-route-id="{{ $route->id }}"
                                                data-route-name="{{ $route->route_name }}" title="Delete Route">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Form (Hidden) -->
                                        <form id="delete-form-{{ $route->id }}"
                                            action="{{ route('admin.routes.destroy', $route) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-signpost display-4 d-block mb-2"></i>
                                            No routes found.
                                        </div>
                                        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-lg me-1"></i> Create First Route
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert for delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-route');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const routeId = this.getAttribute('data-route-id');
                    const routeName = this.getAttribute('data-route-name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete route "${routeName}". This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${routeId}`).submit();
                        }
                    });
                });
            });

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
    </style>
@endpush
