@extends('layouts.admin')

@section('content')

<h2>Stock List</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>QR</th>
        <th>Item Name</th>
        <th>Branch</th>
        <th>System Qty</th>
        <th>Action</th>
    </tr>

    @foreach($stocks as $stock)
        <tr>
            <td>{{ $stock->barcode }}</td>
            <td>{{ $stock->item_name }}</td>
            <td>{{ $stock->branch->branch_name ?? '' }}</td>
            <td>{{ $stock->system_quantity }}</td>
            <td>
                <a href="/admin/stocks/edit/{{ $stock->id }}">Edit</a>

                <form method="POST"
                      action="/admin/stocks/delete/{{ $stock->id }}"
                      style="display:inline">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Delete this stock?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

@endsection
