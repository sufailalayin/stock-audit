@extends('layouts.admin')

@section('content')

<h4 class="mb-3">
    Audit Result – {{ $file->file_name }}
</h4>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Barcode</th>
            <th>Item</th>
            <th>System Qty</th>
            <th>Entered Qty</th>
            <th>Difference</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @foreach($stocks as $stock)

        @php
            // SAFE: may be null
            $count = $stock->stockCounts->first();
        @endphp

        <tr class="
            @if(!$count)
                table-warning
            @elseif($count->status === 'mismatch')
                table-danger
            @else
                table-success
            @endif
        ">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $stock->barcode }}</td>
            <td>{{ $stock->item_name }}</td>
            <td>{{ $stock->system_quantity }}</td>

            <td>
                {{ $count ? $count->entered_quantity : '-' }}
            </td>

            <td>
                {{ $count ? $count->difference : '-' }}
            </td>

            <td>
                @if(!$count)
                    <span class="badge bg-warning">Pending</span>
                @elseif($count->status === 'mismatch')
                    <span class="badge bg-danger">Mismatch</span>
                @else
                    <span class="badge bg-success">Matched</span>
                @endif
            </td>
            <td>
    <a href="{{ route('admin.stocks.edit', $stock->id) }}"
       class="btn btn-sm btn-warning">
        Edit
    </a>

    <form action="{{ route('admin.stocks.delete', $stock->id) }}"
          method="POST"
          style="display:inline"
          onsubmit="return confirm('Delete this record?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>
        </tr>

    @endforeach
    
    </tbody>
</table>

@endsection