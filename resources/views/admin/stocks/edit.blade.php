@extends('layouts.admin')

@section('content')
<div class="container">

    <h4 class="mb-4">Edit Stock (Entered Quantity)</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered mb-4">
                <tr>
                    <th>Barcode</th>
                    <td>{{ $stock->barcode }}</td>
                </tr>
                <tr>
                    <th>Item Name</th>
                    <td>{{ $stock->item_name }}</td>
                </tr>
                <tr>
                    <th>System Quantity</th>
                    <td>{{ $stock->system_quantity }}</td>
                </tr>
            </table>

            <form method="POST" action="{{ url('/admin/stocks/'.$stock->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Entered Quantity</label>
                    <input type="number"
                           name="entered_quantity"
                           class="form-control"
                           required
                           value="{{ optional($stock->stockCounts->first())->entered_quantity }}">
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    Back
                </a>
            </form>

        </div>
    </div>

</div>
@endsection