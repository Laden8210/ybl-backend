@extends('layouts.admin')

@section('title', 'Staff')
@section('page_title', 'Staff')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Staff Accounts</h2>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Create Account</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Jane Supervisor</td>
                        <td><span class="badge text-bg-info">Supervisor</span></td>
                        <td>jane@ybl.example</td>
                        <td><span class="badge text-bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-danger">Disable</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Mike Driver</td>
                        <td><span class="badge text-bg-secondary">Driver</span></td>
                        <td>mike@ybl.example</td>
                        <td><span class="badge text-bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-danger">Disable</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
