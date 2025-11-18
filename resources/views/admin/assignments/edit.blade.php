@extends('layouts.admin')

@section('title', 'Assignments')
@section('page_title', 'Assignments')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h6">Assign Staff to Buses</h2>
            <form class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Bus</label>
                    <select class="form-select">
                        <option>YBL-1234</option>
                        <option>YBL-5678</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Driver</label>
                    <select class="form-select">
                        <option>Mike Driver</option>
                        <option>Sarah Driver</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Conductor</label>
                    <select class="form-select">
                        <option>Chris Conductor</option>
                        <option>Alex Conductor</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Assign</button>
                </div>
            </form>
            <hr>
            <h2 class="h6">Current Assignments</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Bus</th>
                            <th>Driver</th>
                            <th>Conductor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>YBL-1234</td>
                            <td>Mike Driver</td>
                            <td>Chris Conductor</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
