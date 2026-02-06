@extends('layouts.admin')

@section('content')
<h2>Audit Files - {{ $branch->name }}</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>File Name</th>
        <th>Total Items</th>
        <th>Action</th>
    </tr>

    @foreach($files as $file)
    <tr>
        <td>{{ $file->file_name }}</td>
        <td>{{ $file->total_items }}</td>
        <td>
            <a href="#">Open</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection