@extends('layouts.admin')

@section('title', 'Bus Logs')
@section('page_title', 'Bus Logs')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Bus</th>
                            <th>Event</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-10-05 08:20</td>
                            <td>YBL-1234</td>
                            <td>Departure</td>
                            <td>Started route YBL-23</td>
                        </tr>
                        <tr>
                            <td>2025-10-05 09:05</td>
                            <td>YBL-5678</td>
                            <td>Stop</td>
                            <td>Arrived at Station 4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
