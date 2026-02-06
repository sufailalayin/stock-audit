@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">
            📊 Audit Result
            <span class="text-muted">— {{ $auditFile->file_name }}</span>
        </h4>
        <a href="{{ route('admin.audit.export.excel', $auditFile->id) }}"
   class="btn btn-success btn-sm">
   Export Excel
</a>
<a href="{{ route('admin.audit.export.pdf', $auditFile->id) }}"
   class="btn btn-danger btn-sm">
   Export PDF
</a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
            ← Back
        </a>
       
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        {{-- Total Items --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Items</h6>
                    <h3 class="fw-bold">
                        {{ $auditFile->stockCounts->count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Matched --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body text-success">
                    <h6>Matched</h6>
                    <h3 class="fw-bold">
                        {{ $auditFile->stockCounts->where('status','matched')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Mismatch --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body text-danger">
                    <h6>Mismatches</h6>
                    <h3 class="fw-bold">
                        {{ $auditFile->stockCounts->where('status','mismatch')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body text-warning">
                    <h6>Pending</h6>
                    <h3 class="fw-bold">
                        {{ $auditFile->stockCounts->where('status','pending')->count() }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Table -->
    <div class="card shadow border-0">
        <div class="card-body p-0">

            <table class="table table-bordered align-middle mb-0">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>#</th>
                        <th>Barcode</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Size</th>
                        <th>System Qty</th>
                        <th>Counted Qty</th>
                        <th>Difference</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

               <tbody>
@forelse($stockCounts as $count)
    @php
        $stock = $count->stock;

              if ($count->status === 'matched') {
        $bg = '#38db7c';   // soft dark green
    } elseif ($count->status === 'mismatch') {
        $bg = '#f3b5bb';   // soft dark red
    } else {
        $bg = '#f8f9fa';   // soft grey (pending)
    }
@endphp

    <tr>
        <td style="background-color: {{ $bg }};" class="text-center">
            {{ $loop->iteration }}
        </td>

        <td style="background-color: {{ $bg }};" class="fw-bold">
            {{ $stock->barcode ?? '-' }}
        </td>

        <td style="background-color: {{ $bg }};">
            {{ $stock->item_name ?? '-' }}
        </td>

        <td style="background-color: {{ $bg }};">
            {{ $stock->brand_name ?? '-' }}
        </td>

        <td style="background-color: {{ $bg }};">
            {{ $stock->size ?? '-' }}
        </td>

        <td style="background-color: {{ $bg }};" class="text-center">
            {{ $stock->system_quantity ?? 0 }}
        </td>

        <td style="background-color: {{ $bg }};" class="text-center">
            {{ $count->entered_quantity }}
        </td>

        <td style="background-color: {{ $bg }};" class="text-center">
            {{ $count->difference }}
        </td>

        <td style="background-color: {{ $bg }};" class="text-center">
            <span class="badge
                {{ $count->status === 'matched'
                    ? 'bg-success'
                    : ($count->status === 'mismatch'
                        ? 'bg-danger'
                        : 'bg-warning text-dark') }}">
                {{ ucfirst($count->status) }}
            </span>
        </td>

        <td style="background-color: {{ $bg }};" class="text-center">
            <a href="{{ route('admin.stocks.edit-count', $count->id) }}"
               class="btn btn-sm btn-outline-primary">
                Edit
            </a>

            <form action="{{ route('admin.stocks.delete-count', $count->id) }}"
                  method="POST"
                  class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Delete this entry?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted py-4">
            No audit data found
        </td>
    </tr>
@endforelse
</tbody>

            </table>

        </div>
    </div>

</div>

{{-- ROW COLORS --}}
<style>
.row-matched {
    background-color: #e6fffa !important;
}
.row-mismatch {
    background-color: #ffe6e6 !important;
}
.row-pending {
    background-color: #fff3cd !important;
}
</style>

@endsection