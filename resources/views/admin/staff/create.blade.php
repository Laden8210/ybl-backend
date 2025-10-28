@extends('layouts.admin')

@section('title', 'Create Staff Account')
@section('page_title', 'Create Staff Account')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="John Doe">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="john@ybl.example">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select class="form-select">
                            <option>Supervisor</option>
                            <option>Driver</option>
                            <option>Conductor</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
