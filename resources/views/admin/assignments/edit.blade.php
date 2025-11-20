@extends('layouts.admin')

@section('title', 'Assignments')
@section('page_title', 'Assignments')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h6">Assign Staff to Buses</h2>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form class="row g-3" method="POST" action="{{ route('admin.assignments.update', $assignment->id) }}">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Bus</label>
                    <select name="bus_id" class="form-select @error('bus_id') is-invalid @enderror">
                        <option value="">Select Bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id', $assignment->bus_id) == $bus->id ? 'selected' : '' }}>
                                {{ $bus->name ?? $bus->license_plate ?? $bus->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Driver</label>
                    <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror">
                        <option value="">Select Driver</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $assignment->driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} ({{ $driver->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Conductor</label>
                    <select name="conductor_id" class="form-select @error('conductor_id') is-invalid @enderror">
                        <option value="">Select Conductor</option>
                        @foreach($conductors as $conductor)
                            <option value="{{ $conductor->id }}" {{ old('conductor_id', $assignment->conductor_id) == $conductor->id ? 'selected' : '' }}>
                                {{ $conductor->name }} ({{ $conductor->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('conductor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assignment Date</label>
                    <input type="date" name="assignment_date" class="form-control @error('assignment_date') is-invalid @enderror" value="{{ old('assignment_date', $assignment->assignment_date ? $assignment->assignment_date->format('Y-m-d') : '') }}">
                    @error('assignment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Notes</label>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $assignment->notes) }}">
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Cancel</a>
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
