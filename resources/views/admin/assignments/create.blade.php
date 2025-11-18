@extends('layouts.admin')

@section('title', 'Bus Assignments')
@section('page_title', 'Bus Assignments Management')

@section('content')
<div class="container-fluid">
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-link me-2"></i>Assign Staff to Bus</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.assignments.store') }}" method="POST" id="assignmentForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="bus_id" class="form-label">Bus <span class="text-danger">*</span></label>
                                <select class="form-select @error('bus_id') is-invalid @enderror"
                                        id="bus_id" name="bus_id" required>
                                    <option value="">Select Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}"
                                            {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                            {{ $bus->bus_number }} ({{ $bus->license_plate }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="assignment_date" class="form-label">Assignment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('assignment_date') is-invalid @enderror"
                                       id="assignment_date" name="assignment_date"
                                       value="{{ old('assignment_date', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('assignment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                <select class="form-select @error('driver_id') is-invalid @enderror"
                                        id="driver_id" name="driver_id" required>
                                    <option value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }} ({{ $driver->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="conductor_id" class="form-label">Conductor <span class="text-danger">*</span></label>
                                <select class="form-select @error('conductor_id') is-invalid @enderror"
                                        id="conductor_id" name="conductor_id" required>
                                    <option value="">Select Conductor</option>
                                    @foreach($conductors as $conductor)
                                        <option value="{{ $conductor->id }}"
                                            {{ old('conductor_id') == $conductor->id ? 'selected' : '' }}>
                                            {{ $conductor->name }} ({{ $conductor->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('conductor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="2"
                                          placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-link me-1"></i> Create Assignment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Current Assignments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">#</th>
                                    <th>Bus Details</th>
                                    <th>Driver</th>
                                    <th>Conductor</th>
                                    <th>Assignment Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $index => $assignment)
                                    <tr>
                                        <td class="px-4">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="bi bi-bus-front text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $assignment->bus->bus_number }}</h6>
                                                    <small class="text-muted">{{ $assignment->bus->license_plate }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-person text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 small">{{ $assignment->driver->name }}</h6>
                                                    <small class="text-muted">{{ $assignment->driver->employee_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-person text-dark"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 small">{{ $assignment->conductor->name }}</h6>
                                                    <small class="text-muted">{{ $assignment->conductor->employee_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $assignment->assignment_date->format('M d, Y') }}
                                            </span>
                                            @if($assignment->assignment_date->isToday())
                                                <span class="badge bg-success ms-1">Today</span>
                                            @elseif($assignment->assignment_date->isPast())
                                                <span class="badge bg-secondary ms-1">Past</span>
                                            @else
                                                <span class="badge bg-info ms-1">Upcoming</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-success',
                                                    'completed' => 'bg-secondary',
                                                    'cancelled' => 'bg-danger'
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusColors[$assignment->status] }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.assignments.edit', $assignment) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit Assignment">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

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
                                                        <li>
                                                            <form action="{{ route('admin.assignments.toggle-status', $assignment) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="dropdown-item {{ $assignment->status === 'active' ? 'active' : '' }}">
                                                                    <i class="bi bi-play-circle text-success me-2"></i>Active
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.assignments.toggle-status', $assignment) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="dropdown-item {{ $assignment->status === 'completed' ? 'active' : '' }}">
                                                                    <i class="bi bi-check-circle text-secondary me-2"></i>Completed
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.assignments.toggle-status', $assignment) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <button type="submit" class="dropdown-item {{ $assignment->status === 'cancelled' ? 'active' : '' }}">
                                                                    <i class="bi bi-x-circle text-danger me-2"></i>Cancelled
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-assignment"
                                                        data-assignment-id="{{ $assignment->id }}"
                                                        data-bus-number="{{ $assignment->bus->bus_number }}"
                                                        data-assignment-date="{{ $assignment->assignment_date->format('M d, Y') }}"
                                                        title="Delete Assignment">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Form (Hidden) -->
                                            <form id="delete-form-{{ $assignment->id }}"
                                                  action="{{ route('admin.assignments.destroy', $assignment) }}"
                                                  method="POST"
                                                  class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @if($assignment->notes)
                                    <tr>
                                        <td colspan="7" class="bg-light">
                                            <small class="text-muted">
                                                <i class="bi bi-chat-text me-1"></i>
                                                <strong>Notes:</strong> {{ $assignment->notes }}
                                            </small>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-link display-4 d-block mb-2"></i>
                                                No assignments found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignmentDate = document.getElementById('assignment_date');
    const driverSelect = document.getElementById('driver_id');
    const conductorSelect = document.getElementById('conductor_id');

    // Function to update available staff based on selected date
    function updateAvailableStaff() {
        const date = assignmentDate.value;

        if (!date) return;

        fetch(`/admin/assignments/available-staff?date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Update drivers
                driverSelect.innerHTML = '<option value="">Select Driver</option>';
                data.drivers.forEach(driver => {
                    const option = new Option(`${driver.name} (${driver.employee_id})`, driver.id);
                    driverSelect.add(option);
                });

                // Update conductors
                conductorSelect.innerHTML = '<option value="">Select Conductor</option>';
                data.conductors.forEach(conductor => {
                    const option = new Option(`${conductor.name} (${conductor.employee_id})`, conductor.id);
                    conductorSelect.add(option);
                });
            })
            .catch(error => console.error('Error fetching available staff:', error));
    }

    // Update staff when date changes
    assignmentDate.addEventListener('change', updateAvailableStaff);

    // SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-assignment');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.getAttribute('data-assignment-id');
            const busNumber = this.getAttribute('data-bus-number');
            const assignmentDate = this.getAttribute('data-assignment-date');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to remove assignment for bus "${busNumber}" on ${assignmentDate}. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${assignmentId}`).submit();
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
