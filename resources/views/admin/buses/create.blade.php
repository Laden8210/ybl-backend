@extends('layouts.admin')

@section('title', 'Add Bus')
@section('page_title', 'Add Bus')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Plate Number</label>
                        <input type="text" class="form-control" placeholder="YBL-1234">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Model</label>
                        <input type="text" class="form-control" placeholder="Volvo 7700">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" class="form-control" placeholder="45">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>Active</option>
                            <option>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
