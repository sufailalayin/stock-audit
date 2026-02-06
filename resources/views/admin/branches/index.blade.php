@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>Branches</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.branches.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="text"
                       name="branch_name"
                       class="form-control"
                       placeholder="Enter Branch Name"
                       required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Add Branch</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Branch Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->id }}</td>
                <td>
   <a href="{{ route('admin.branches.files', $branch->id) }}">
    {{ $branch->branch_name }}
</a>
       </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection