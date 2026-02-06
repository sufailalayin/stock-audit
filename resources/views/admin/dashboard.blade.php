@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Branch-wise Stock Audit Dashboard</h2>

@if($branches->count() === 0)
    <p class="text-muted">No branches found.</p>
@endif

@foreach($branches as $branch)

    @php
        // Show branch ONLY if it has pending items
        $hasPending = false;
        foreach ($branch->auditFiles as $file) {
            if ($file->pending_items > 0) {
                $hasPending = true;
                break;
            }
        }
    @endphp

    @if(!$hasPending)
        @continue
    @endif

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <b>{{ $branch->branch_name }}</b>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>File</th>
                        <th>Total</th>
                        <th>Counted</th>
                        <th>Pending</th>
                        <th>Mismatches</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($branch->auditFiles as $file)

                    @if($file->pending_items === 0)
                        @continue
                    @endif

                    <tr>
                        <td>{{ $file->file_name }}</td>
                        <td>{{ $file->total_items }}</td>

                        <td class="text-success fw-bold">
                            {{ $file->counted_items }}
                        </td>

                        <td class="text-warning fw-bold">
                            {{ $file->pending_items }}
                        </td>

                        <td class="text-danger fw-bold">
                            {{ $file->mismatch_items }}
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">In Progress</span>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endforeach

@endsection