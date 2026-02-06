@extends('layouts.admin')

@section('content')

<h2>Edit Audit Entry</h2>

@if ($errors->any())
    <div style="color:red; margin-bottom:10px;">
        {{ $errors->first() }}
    </div>
@endif

<div style="margin-bottom:15px; padding:10px; border:1px solid #ccc;">
    <p><b>Branch:</b> {{ $result->stock->branch->branch_name ?? '' }}</p>
    <p><b>Staff:</b> {{ $result->user->name ?? '' }}</p>
    <p><b>QR Code:</b> {{ $result->stock->barcode }}</p>
    <p><b>Item Name:</b> {{ $result->stock->item_name }}</p>
    <p><b>System Quantity:</b> {{ $result->stock->system_quantity }}</p>
</div>

<form method="POST" action="/admin/results/update/{{ $result->id }}">
    @csrf

    <label>Entered Quantity</label><br>
    <input type="number"
           name="entered_quantity"
           value="{{ $result->entered_quantity }}"
           min="0"
           required>

    <br><br>

    <button type="submit">Update Audit</button>
    <a href="/admin/results" style="margin-left:10px;">Cancel</a>
</form>

@endsection
