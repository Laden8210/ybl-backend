@extends('layouts.admin')

@section('title', 'Edit Bus')
@section('page_title', 'Edit Bus')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit Bus - {{ $bus->bus_number }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.buses.update', $bus) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bus_number" class="form-label">Bus Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bus_number') is-invalid @enderror"
                                        id="bus_number" name="bus_number" value="{{ old('bus_number', $bus->bus_number) }}"
                                        placeholder="e.g., BUS-001" required>
                                    @error('bus_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="license_plate" class="form-label">License Plate <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('license_plate') is-invalid @enderror"
                                        id="license_plate" name="license_plate"
                                        value="{{ old('license_plate', $bus->license_plate) }}" placeholder="e.g., YBL-1234"
                                        required>
                                    @error('license_plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="model" class="form-label">Bus Model <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                        id="model" name="model" value="{{ old('model', $bus->model) }}"
                                        placeholder="e.g., Volvo 7700" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label">Passenger Capacity <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                        id="capacity" name="capacity" value="{{ old('capacity', $bus->capacity) }}"
                                        min="1" max="100" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control @error('color') is-invalid @enderror"
                                        id="color" name="color" value="{{ old('color', $bus->color) }}"
                                        placeholder="e.g., Blue, Red">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Features</label>
                                <div class="row">
                                    @php
                                        $features = [
                                            'AC',
                                            'WiFi',
                                            'USB Ports',
                                            'TV',
                                            'GPS',
                                            'Wheelchair Accessible',
                                            'Luggage Storage',
                                        ];
                                        $currentFeatures = json_decode($bus->features, true) ?? [];
                                    @endphp
                                    @foreach ($features as $feature)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="features[]"
                                                    value="{{ $feature }}"
                                                    id="feature_{{ \Illuminate\Support\Str::slug($feature) }}"
                                                    {{ in_array($feature, old('features', $currentFeatures)) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="feature_{{ \Illuminate\Support\Str::slug($feature) }}">
                                                    {{ $feature }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('features')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>




                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Fleet
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Bus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
