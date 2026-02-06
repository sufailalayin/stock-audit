@extends('layouts.admin')

@section('content')
<h2>Files – {{ $branch->branch_name }}</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>File Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($files as $file)
        <tr>
            <td>{{ $file->id }}</td>
            <td>{{ $file->file_name }}</td>
            <td>
                <td>
    <!-- Open Excel File -->
    <a href="{{ route('admin.audit.files.open', $file->file_name) }}"
       class="btn btn-sm btn-primary">
       Open File
    </a>

    <!-- Go to Counting -->
    <a href="{{ route('staff.count', $file->id) }}"
       class="btn btn-sm btn-success">
       Count
    </a>
   </td>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection