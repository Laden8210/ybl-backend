@extends('layouts.admin')

@section('title', 'Create Schedule')
@section('page_title', 'Create Schedule')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Create New Schedule</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bus_id" class="form-label">Bus <span class="text-danger">*</span></label>
                                    <select class="form-select @error('bus_id') is-invalid @enderror"
                                            id="bus_id" name="bus_id" required>
                                        <option value="">Select Bus</option>
                                        @foreach($buses as $bus)
                                            <option value="{{ $bus->id }}"
                                                {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                                {{ $bus->bus_number }} ({{ $bus->license_plate }}) - Capacity: {{ $bus->capacity }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bus_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="route_id" class="form-label">Route <span class="text-danger">*</span></label>
                                    <select class="form-select @error('route_id') is-invalid @enderror"
                                            id="route_id" name="route_id" required>
                                        <option value="">Select Route</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}"
                                                {{ old('route_id') == $route->id ? 'selected' : '' }}
                                                data-distance="{{ $route->distance }}"
                                                data-duration="{{ $route->estimated_duration }}">
                                                {{ $route->route_name }} - {{ $route->formatted_distance }} ({{ $route->formatted_duration }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="departure_time" class="form-label">Departure Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('departure_time') is-invalid @enderror"
                                           id="departure_time" name="departure_time"
                                           value="{{ old('departure_time') }}" required>
                                    @error('departure_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="arrival_time" class="form-label">Arrival Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('arrival_time') is-invalid @enderror"
                                           id="arrival_time" name="arrival_time"
                                           value="{{ old('arrival_time') }}" required>
                                    @error('arrival_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                                    <select class="form-select @error('day_of_week') is-invalid @enderror"
                                            id="day_of_week" name="day_of_week" required>
                                        <option value="">Select Day</option>
                                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                            <option value="{{ $day }}"
                                                {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                                {{ ucfirst($day) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day_of_week')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="effective_date" class="form-label">Effective Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('effective_date') is-invalid @enderror"
                                           id="effective_date" name="effective_date"
                                           value="{{ old('effective_date', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('effective_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date"
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty for ongoing schedule</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox"
                                               id="is_recurring" name="is_recurring" value="1"
                                               {{ old('is_recurring') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_recurring">
                                            Recurring Schedule
                                        </label>
                                    </div>
                                    <div class="form-text">If checked, this schedule will repeat weekly</div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Preview -->
                        <div class="card border-info mt-4" id="schedule-preview" style="display: none;">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="bi bi-eye me-2"></i>Schedule Preview</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Bus:</strong> <span id="preview-bus">-</span><br>
                                        <strong>Route:</strong> <span id="preview-route">-</span><br>
                                        <strong>Distance:</strong> <span id="preview-distance">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Day:</strong> <span id="preview-day">-</span><br>
                                        <strong>Time:</strong> <span id="preview-time">-</span><br>
                                        <strong>Duration:</strong> <span id="preview-duration">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Schedules
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Create Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const busSelect = document.getElementById('bus_id');
    const routeSelect = document.getElementById('route_id');
    const departureTime = document.getElementById('departure_time');
    const arrivalTime = document.getElementById('arrival_time');
    const daySelect = document.getElementById('day_of_week');
    const previewCard = document.getElementById('schedule-preview');

    function updatePreview() {
        const selectedBus = busSelect.options[busSelect.selectedIndex];
        const selectedRoute = routeSelect.options[routeSelect.selectedIndex];
        const selectedDay = daySelect.options[daySelect.selectedIndex];

        if (selectedBus.value && selectedRoute.value && selectedDay.value && departureTime.value && arrivalTime.value) {
            // Show preview
            previewCard.style.display = 'block';

            // Update preview content
            document.getElementById('preview-bus').textContent = selectedBus.text;
            document.getElementById('preview-route').textContent = selectedRoute.text.split(' - ')[0];
            document.getElementById('preview-distance').textContent = selectedRoute.getAttribute('data-distance') + ' km';
            document.getElementById('preview-day').textContent = selectedDay.text;

            // Format time
            const depTime = new Date('1970-01-01T' + departureTime.value);
            const arrTime = new Date('1970-01-01T' + arrivalTime.value);
            document.getElementById('preview-time').textContent =
                depTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) +
                ' to ' +
                arrTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

            // Calculate duration
            const durationMs = arrTime - depTime;
            const durationMinutes = Math.round(durationMs / 60000);
            const hours = Math.floor(durationMinutes / 60);
            const minutes = durationMinutes % 60;

            let durationText = '';
            if (hours > 0) {
                durationText += hours + 'h ';
            }
            durationText += minutes + 'm';
            document.getElementById('preview-duration').textContent = durationText;
        } else {
            previewCard.style.display = 'none';
        }
    }

    // Add event listeners for preview updates
    busSelect.addEventListener('change', updatePreview);
    routeSelect.addEventListener('change', updatePreview);
    departureTime.addEventListener('change', updatePreview);
    arrivalTime.addEventListener('change', updatePreview);
    daySelect.addEventListener('change', updatePreview);

    // Auto-set arrival time based on route duration
    routeSelect.addEventListener('change', function() {
        const selectedRoute = this.options[this.selectedIndex];
        const routeDuration = parseInt(selectedRoute.getAttribute('data-duration'));

        if (routeDuration && departureTime.value) {
            const depTime = new Date('1970-01-01T' + departureTime.value);
            const arrTime = new Date(depTime.getTime() + routeDuration * 60000);

            // Format to HH:MM
            const hours = arrTime.getHours().toString().padStart(2, '0');
            const minutes = arrTime.getMinutes().toString().padStart(2, '0');
            arrivalTime.value = hours + ':' + minutes;

            updatePreview();
        }
    });

    // Form validation
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        if (arrivalTime.value <= departureTime.value) {
            e.preventDefault();
            alert('Arrival time must be after departure time!');
            return false;
        }
    });
});
</script>
@endpush
