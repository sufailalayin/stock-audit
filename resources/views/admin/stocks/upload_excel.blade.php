@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Upload Stock (Excel)</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.stocks.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Branch</label>
        <select name="branch_id" class="form-control" required>
            <option value="">-- Select Branch --</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Excel File</label>
        <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Upload Excel
    </button>
</form>
@endsection