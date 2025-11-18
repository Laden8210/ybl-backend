@extends('layouts.admin')

@section('title', 'Bus Fleet Management')
@section('page_title', 'Bus Fleet Management')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h5 mb-0 text-gray-800">Bus Fleet</h2>
            <a href="{{ route('admin.buses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Bus
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

        <div class="card border-0 shadow-sm min-w-8/12">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">#</th>
                                <th>Bus Number</th>
                                <th>License Plate</th>
                                <th>Model</th>
                                <th>Capacity</th>
                                <th>Color</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buses as $index => $bus)
                                <tr>
                                    <td class="px-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3 px-2 py-2">
                                                <i class="bi bi-bus-front text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $bus->bus_number }}</h6>
                                                @if ($bus->features)
                                                    <small class="text-muted">
                                                        {{ implode(', ', json_decode($bus->features, true) ?? []) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">{{ $bus->license_plate }}</span>
                                    </td>
                                    <td>{{ $bus->model }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="bi bi-people me-1"></i>{{ $bus->capacity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($bus->color)
                                            <div class="d-flex align-items-center">
                                                <div class="color-swatch me-2"
                                                    style="background-color: {{ $bus->color }}"></div>
                                                <span>{{ $bus->color }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-success',
                                                'maintenance' => 'bg-warning text-dark',
                                                'inactive' => 'bg-danger',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$bus->status] }}">
                                            {{ ucfirst($bus->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.buses.edit', $bus) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- Status Dropdown -->

                                            <div class="dropdown dropdown-center">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport"
                                                    data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false"
                                                    title="Change Status">
                                                    <i class="bi bi-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form action="{{ route('admin.buses.toggle-status', $bus) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $bus->status === 'active' ? 'active' : '' }}">
                                                                <i class="bi bi-check-circle text-success me-2"></i>Active
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.buses.toggle-status', $bus) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="maintenance">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $bus->status === 'maintenance' ? 'active' : '' }}">
                                                                <i class="bi bi-tools text-warning me-2"></i>Maintenance
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.buses.toggle-status', $bus) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="inactive">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $bus->status === 'inactive' ? 'active' : '' }}">
                                                                <i class="bi bi-x-circle text-danger me-2"></i>Inactive
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                            <button type="button" class="btn btn-sm btn-outline-danger delete-bus"
                                                data-bus-id="{{ $bus->id }}" data-bus-number="{{ $bus->bus_number }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Form (Hidden) -->
                                        <form id="delete-form-{{ $bus->id }}"
                                            action="{{ route('admin.buses.destroy', $bus) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-bus-front display-4 d-block mb-2"></i>
                                            No buses found in the fleet.
                                        </div>
                                        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-lg me-1"></i> Add First Bus
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
            const deleteButtons = document.querySelectorAll('.delete-bus');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const busId = this.getAttribute('data-bus-id');
                    const busNumber = this.getAttribute('data-bus-number');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete bus "${busNumber}". This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${busId}`).submit();
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

        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #dee2e6;
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

        .dropdown-menu .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
@endpush
