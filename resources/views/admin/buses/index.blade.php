@extends('layouts.admin')

@section('title', 'Buses')
@section('page_title', 'Buses')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Bus Fleet</h2>
        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Bus</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Plate</th>
                            <th>Model</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>YBL-1234</td>
                            <td>Volvo 7700</td>
                            <td>45</td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>YBL-5678</td>
                            <td>Mercedes Citaro</td>
                            <td>40</td>
                            <td><span class="badge text-bg-secondary">Maintenance</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
