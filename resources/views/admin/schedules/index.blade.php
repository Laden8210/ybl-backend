@extends('layouts.admin')

@section('title', 'Schedule Management')
@section('page_title', 'Schedule Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 text-gray-800">Bus Schedules</h2>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create Schedule
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Schedule Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Day of Week</label>
                    <select class="form-select" id="day-filter">
                        <option value="">All Days</option>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="status-filter">
                        <option value="">All Statuses</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="departed">Departed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bus</label>
                    <select class="form-select" id="bus-filter">
                        <option value="">All Buses</option>
                        @foreach($schedules->unique('bus_id') as $schedule)
                            <option value="{{ $schedule->bus_id }}">{{ $schedule->bus->bus_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100" id="reset-filters">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="schedules-table">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Bus & Route</th>
                            <th>Schedule</th>
                            <th>Day</th>
                            <th>Timings</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $schedule)
                            <tr class="schedule-row"
                                data-day="{{ $schedule->day_of_week }}"
                                data-status="{{ $schedule->status }}"
                                data-bus="{{ $schedule->bus_id }}">
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm px-2 py-1 bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-bus-front text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $schedule->bus->bus_number }}</h6>
                                            <small class="text-muted">{{ $schedule->bus->license_plate }}</small>
                                            <br>
                                            <small class="text-primary">{{ $schedule->route->route_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">From</small><br>
                                    <strong>{{ $schedule->route->start_point }}</strong>
                                    <br>
                                    <small class="text-muted">To</small><br>
                                    <strong>{{ $schedule->route->end_point }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark text-capitalize">
                                        {{ $schedule->day_of_week }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</strong>
                                            <br>
                                            <small class="text-muted">to</small>
                                            <br>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($schedule->is_recurring)
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-repeat me-1"></i> Recurring
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-calendar-event me-1"></i> One-time
                                        </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        Effective: {{ $schedule->effective_date->format('M d, Y') }}
                                    </small>
                                    @if($schedule->end_date)
                                        <br>
                                        <small class="text-muted">
                                            Until: {{ $schedule->end_date->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-primary',
                                            'departed' => 'bg-info',
                                            'in_progress' => 'bg-warning text-dark',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$schedule->status] }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Status Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    title="Change Status">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach(['scheduled', 'departed', 'in_progress', 'completed', 'cancelled'] as $status)
                                                    <li>
                                                        <form action="{{ route('admin.schedules.toggle-status', $schedule) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="{{ $status }}">
                                                            <button type="submit" class="dropdown-item {{ $schedule->status === $status ? 'active' : '' }}">
                                                                <i class="bi bi-circle-fill text-{{ $statusColors[$status] }} me-2"></i>
                                                                {{ ucfirst($status) }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Schedule">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-schedule"
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-bus-number="{{ $schedule->bus->bus_number }}"
                                                data-route-name="{{ $schedule->route->route_name }}"
                                                data-day="{{ $schedule->day_of_week }}"
                                                title="Delete Schedule">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Form (Hidden) -->
                                    <form id="delete-form-{{ $schedule->id }}"
                                          action="{{ route('admin.schedules.destroy', $schedule) }}"
                                          method="POST"
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
                                        <i class="bi bi-calendar display-4 d-block mb-2"></i>
                                        No schedules found.
                                    </div>
                                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Create First Schedule
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
    // Filter functionality
    const dayFilter = document.getElementById('day-filter');
    const statusFilter = document.getElementById('status-filter');
    const busFilter = document.getElementById('bus-filter');
    const resetButton = document.getElementById('reset-filters');
    const scheduleRows = document.querySelectorAll('.schedule-row');

    function filterSchedules() {
        const selectedDay = dayFilter.value;
        const selectedStatus = statusFilter.value;
        const selectedBus = busFilter.value;

        scheduleRows.forEach(row => {
            const dayMatch = !selectedDay || row.getAttribute('data-day') === selectedDay;
            const statusMatch = !selectedStatus || row.getAttribute('data-status') === selectedStatus;
            const busMatch = !selectedBus || row.getAttribute('data-bus') === selectedBus;

            if (dayMatch && statusMatch && busMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    dayFilter.addEventListener('change', filterSchedules);
    statusFilter.addEventListener('change', filterSchedules);
    busFilter.addEventListener('change', filterSchedules);

    resetButton.addEventListener('click', function() {
        dayFilter.value = '';
        statusFilter.value = '';
        busFilter.value = '';
        filterSchedules();
    });

    // SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-schedule');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-schedule-id');
            const busNumber = this.getAttribute('data-bus-number');
            const routeName = this.getAttribute('data-route-name');
            const day = this.getAttribute('data-day');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete schedule for bus "${busNumber}" on route "${routeName}" (${day}). This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${scheduleId}`).submit();
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
.dropdown-menu .dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}
</style>
@endpush
