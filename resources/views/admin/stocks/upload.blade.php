@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Upload Stock (Excel)</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.stocks.upload.store') }}"
          enctype="multipart/form-data">
        @csrf

        <!-- Branch -->
        <div class="mb-3">
            <label class="form-label">Branch</label>
            <select name="branch_id" class="form-control" required>
                <option value="">-- Select Branch --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">
                        {{ $branch->branch_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Excel File -->
        <div class="mb-3">
            <label class="form-label">Excel File (.xlsx / .csv)</label>
            <input type="file"
                   name="file"
                   class="form-control"
                   accept=".xlsx,.csv"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Upload Excel
        </button>

        <a href="{{ route('admin.branches') }}"
           class="btn btn-secondary ms-2">
            Back
        </a>
    </form>
</div>
@endsection