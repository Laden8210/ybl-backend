@extends('layouts.admin')

@section('title', 'System Logs')
@section('page_title', 'System Logs')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-secondary-body">Activity Logs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Timestamp</th>
                            <th>Type</th>
                            <th>User/Bus</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td class="ps-4 text-nowrap text-secondary">
                                    {{ $activity['timestamp']->format('M d, Y h:i A') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }}">
                                        <i class="{{ $activity['icon'] }} me-1"></i>
                                        {{ ucfirst($activity['type']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle bg-light text-secondary small">
                                            {{ substr($activity['title'], 0, 1) }}
                                        </div>
                                        <span>{{ $activity['title'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($activity['type'] == 'trip')
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            Trip Started
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            Issue Reported
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $activity['description'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x display-4 d-block mb-3"></i>
                                    No logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($activities, 'links'))
            <div class="card-footer bg-white py-3">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
@endsection