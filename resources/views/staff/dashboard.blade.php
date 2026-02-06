@extends('layouts.staff')

@section('content')

<h3>Welcome to {{ auth()->user()->branch->name ?? 'Branch' }}</h3>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;">
    <div class="card"><b>Total Files</b><br>{{ $totalFiles }}</div>
    <div class="card"><b>Pending</b><br>{{ $pendingFiles }}</div>
    <div class="card"><b>Mistakes</b><br>{{ $mistakeFiles }}</div>
    <div class="card"><b>Completed</b><br>{{ $completedFiles }}</div>
</div>

<h3 style="margin-top:20px;">Pending Files</h3>

@foreach($files->whereNull('completed_at') as $file)
@php
$systemQty = $file->stockCounts->sum(fn($c)=>$c->stock->system_quantity ?? 0);
$countedQty = $file->stockCounts->sum('entered_quantity');
@endphp

<div class="card">
    <b>{{ $file->file_name }}</b><br>
    Uploaded: {{ $file->created_at }}<br>
    

    <a class="btn" href="{{ route('staff.count',$file->id) }}">
        ▶ Start Counting
    </a>
</div>
@endforeach

@endsection