@extends('layouts.admin')

@section('content')
<style>
/* FORCE full row coloring */
tr.row-matched > td {
    background-color: #d4edda !important; /* green */
}

tr.row-mismatch > td {
    background-color: #f8d7da !important; /* red */
}

tr.row-pending > td {
    background-color: #fff3cd !important; /* yellow */
}

/* Optional badge colors */
.badge-matched {
    background-color: #198754;
    color: #fff;
}

.badge-mismatch {
    background-color: #dc3545;
    color: #fff;
}

.badge-pending {
    background-color: #ffc107;
    color: #000;
}
</style>

<h2>Audit Results</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

{{-- FILTER --}}
<form method="GET" style="margin-bottom:15px;">
    <label>Filter by Branch:</label>
    <select name="branch_id">
        <option value="">All Branches</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}"
                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
</form>

{{-- STATUS COLORS --}}
<style>
    .row-matched {
        background-color: #e8f5e9; /* light green */
    }
    .row-mismatch {
        background-color: #fdecea; /* light red */
    }
    .row-pending {
        background-color: #fff3cd; /* light yellow */
    }

    .badge {
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-matched {
        background: #2e7d32;
        color: #fff;
    }
    .badge-mismatch {
        background: #c62828;
        color: #fff;
    }
    .badge-pending {
        background: #f9a825;
        color: #000;
    }
</style>

<table border="1" width="100%" cellpadding="8">
    <thead>
        <tr>
            <th>Branch</th>
            <th>Staff</th>
            <th>QR</th>
            <th>Item</th>
            <th>Entered</th>
            <th>Status</th>
            <th>Diff</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($results as $row)

        @php
            $rowClass = match($row->status) {
                'matched'  => 'row-matched',
                'mismatch' => 'row-mismatch',
                default    => 'row-pending',
            };

            $badgeClass = match($row->status) {
                'matched'  => 'badge-matched',
                'mismatch' => 'badge-mismatch',
                default    => 'badge-pending',
            };
        @endphp

        <tr class="{{ $rowClass }}">
            <td>{{ $row->stock->branch->branch_name ?? '' }}</td>
            <td>{{ $row->user->name ?? '' }}</td>
            <td>{{ $row->stock->barcode }}</td>
            <td>{{ $row->stock->item_name }}</td>
            <td>{{ $row->entered_quantity }}</td>

            <td>
                <span class="badge {{ $badgeClass }}">
                    {{ strtoupper($row->status) }}
                </span>
            </td>

            <td>{{ $row->difference }}</td>
            <td>{{ $row->created_at }}</td>

            <td>
                <a href="/admin/results/edit/{{ $row->id }}">Edit</a>
                <form method="POST" action="/admin/results/delete/{{ $row->id }}" style="display:inline">
                    @csrf
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="9" style="text-align:center;">
                No records found
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

@endsection