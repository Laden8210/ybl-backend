@extends('layouts.admin')

@section('title', 'Profile')
@section('page_title', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="h6 mb-0">Account Details</div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" value="Admin User">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="admin@ybl.example">
                        </div>
                        <button class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="h6 mb-0">Change Password</div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <button class="btn btn-secondary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
