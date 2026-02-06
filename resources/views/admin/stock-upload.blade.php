@extends('layouts.admin')

@section('content')

<h2>Upload Stock</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form method="POST" enctype="multipart/form-data">
    @csrf

    <label>Select Branch</label><br>
    <select name="branch_id" required>
        <option value="">-- Select Branch --</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}">
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select>

    <br><br>

    <input type="file" name="file" required>

    <br><br>

    <button type="submit">Upload Excel</button>
</form>

@endsection

