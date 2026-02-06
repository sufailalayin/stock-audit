@extends('layouts.staff')

@section('content')
<style>
/* ===== DASHBOARD ===== */
.dashboard-title {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Summary cards */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.summary-card {
    padding: 15px;
    border-radius: 12px;
    color: #333;
}
.pending-card { background: #fff3cd; }
.mismatch-card { background: #f8d7da; }
.completed-card { background: #d1e7dd; }

.summary-card h2 {
    margin: 0;
    font-size: 26px;
}
.summary-card span {
    font-size: 13px;
    opacity: .8;
}

/* Mobile summary */
@media(max-width:768px){
    .summary-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== TABLE STYLE ===== */
.section-title {
    margin-top: 25px;
    font-size: 18px;
    font-weight: 600;
}

.audit-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.audit-table th {
    text-align: left;
    font-size: 13px;
    color: #666;
    padding: 8px;
}
.audit-table td {
    padding: 10px 8px;
    border-top: 1px solid #eee;
    font-size: 14px;
}

/* Buttons */
.btn-start {
    background: #ffc107;
    color: #000;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
.btn-recount {
    background: #dc3545;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
.btn-view {
    background: #198754;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
</style>

<div class="dashboard-title">📦 Stock Audit</div>

{{-- SUMMARY --}}
<div class="summary-grid">
    <div class="summary-card pending-card">
        <h2>{{ $pendingFiles->count() }}</h2>
        <span>Pending Files</span>
    </div>
    <div class="summary-card mismatch-card">
        <h2>{{ $mismatchFiles->count() }}</h2>
        <span>Mismatch Files</span>
    </div>
    <div class="summary-card completed-card">
        <h2>{{ $completedFiles->count() }}</h2>
        <span>Completed Files</span>
    </div>
</div>

{{-- PENDING --}}
<div class="section-title">⏳ Pending Files</div>
<table class="audit-table">
<tr>
    <th>Audit File</th>
    <th>Type</th>
    <th></th>
</tr>

@forelse($pendingFiles as $file)
<tr>
    <td>{{ $file->file_name }}</td>
    <td><span style="color:#856404;">Pending</span></td>
    <td align="right">
        <a href="{{ route('staff.count', $file->id) }}" class="btn-start">
            Start Count
        </a>
    </td>
</tr>
@empty
<tr><td colspan="3">No pending files</td></tr>
@endforelse
</table>

{{-- MISMATCH --}}
<div class="section-title">⚠️ Mismatch Files</div>
<table class="audit-table">
<tr>
    <th>Audit File</th>
    <th>Type</th>
    <th></th>
</tr>

@forelse($mismatchFiles as $file)
<tr>
    <td>{{ $file->file_name }}</td>
    <td><span style="color:#842029;">Mismatch</span></td>
    <td align="right">
        <a href="{{ route('staff.count', $file->id) }}" class="btn-recount">
            Recount
        </a>
    </td>
</tr>
@empty
<tr><td colspan="3">No mismatch files</td></tr>
@endforelse
</table>

{{-- COMPLETED --}}
<div class="section-title">✅ Completed Files</div>
<table class="audit-table">
<tr>
    <th>Audit File</th>
    <th>Type</th>
    <th></th>
</tr>

@forelse($completedFiles as $file)
<tr>
    <td>{{ $file->file_name }}</td>
    <td><span style="color:#0f5132;">Completed</span></td>
    <td align="right">
        <a href="{{ route('staff.count', $file->id) }}" class="btn-view">
            View
        </a>
    </td>
</tr>
@empty
<tr><td colspan="3">No completed files</td></tr>
@endforelse
</table>

@endsection