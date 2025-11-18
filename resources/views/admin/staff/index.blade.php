@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page_title', 'Staff Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 text-gray-800">Staff Accounts</h2>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create Account
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
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
                            <th>Name</th>
                            <th>Role</th>
                            <th>Employee ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $index => $staffMember)
                            <tr>
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3 px-3 py-2">
                                            <span class="text-white fw-bold">{{ substr($staffMember->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $staffMember->name }}</h6>
                                            @if($staffMember->license_number)
                                                <small class="text-muted">License: {{ $staffMember->license_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'supervisor' => 'bg-info',
                                            'driver' => 'bg-secondary',
                                            'conductor' => 'bg-warning text-dark'
                                        ];
                                    @endphp
                                    <span class="badge {{ $roleColors[$staffMember->role] }}">
                                        {{ ucfirst($staffMember->role) }}
                                    </span>
                                </td>
                                <td>{{ $staffMember->employee_id }}</td>
                                <td>{{ $staffMember->email }}</td>
                                <td>{{ $staffMember->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $staffMember->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $staffMember->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.staff.edit', $staffMember) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm {{ $staffMember->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} toggle-status"
                                                data-staff-id="{{ $staffMember->id }}"
                                                data-staff-name="{{ $staffMember->name }}"
                                                data-current-status="{{ $staffMember->is_active }}"
                                                title="{{ $staffMember->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi {{ $staffMember->is_active ? 'bi-pause' : 'bi-play' }}"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-staff"
                                                data-staff-id="{{ $staffMember->id }}"
                                                data-staff-name="{{ $staffMember->name }}"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Status Toggle Form (Hidden) -->
                                    <form id="status-form-{{ $staffMember->id }}"
                                          action="{{ route('admin.staff.toggle-status', $staffMember) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <!-- Delete Form (Hidden) -->
                                    <form id="delete-form-{{ $staffMember->id }}"
                                          action="{{ route('admin.staff.destroy', $staffMember) }}"
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
                                        <i class="bi bi-people display-4 d-block mb-2"></i>
                                        No staff accounts found.
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for status toggle confirmation
    const statusButtons = document.querySelectorAll('.toggle-status');

    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const staffId = this.getAttribute('data-staff-id');
            const staffName = this.getAttribute('data-staff-name');
            const currentStatus = this.getAttribute('data-current-status') === '1';

            const action = currentStatus ? 'deactivate' : 'activate';
            const actionTitle = currentStatus ? 'Deactivate' : 'Activate';
            const actionText = currentStatus
                ? `You are about to deactivate "${staffName}". They will not be able to access the system until activated again.`
                : `You are about to activate "${staffName}". They will be able to access the system immediately.`;

            const confirmButtonColor = currentStatus ? '#f39c12' : '#28a745';
            const icon = currentStatus ? 'warning' : 'success';

            Swal.fire({
                title: `${actionTitle} Staff Account?`,
                text: actionText,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action} it!`,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`status-form-${staffId}`).submit();
                }
            });
        });
    });

    // SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-staff');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const staffId = this.getAttribute('data-staff-id');
            const staffName = this.getAttribute('data-staff-name');

            Swal.fire({
                title: 'Delete Staff Account?',
                text: `You are about to permanently delete "${staffName}". This action cannot be undone and all associated data will be lost!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        document.getElementById(`delete-form-${staffId}`).submit();
                        // The page will reload after form submission, so we don't need to resolve
                    });
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

/* Custom button hover effects */
.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-outline-success:hover {
    background-color: #28a745;
    border-color: #28a745;
    color: #fff;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}
</style>
@endpush
