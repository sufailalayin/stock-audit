@extends('layouts.admin')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">
    ← Back
</a>
<div class="container">
       
    <div class="card shadow-sm border-0">
        <div class="card-body">
             
            <h4 class="mb-4 fw-bold">✏️ Edit Stock Count</h4>

            {{-- ITEM DETAILS --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Barcode</strong><br>
                    {{ $stockCount->stock->barcode }}
                </div>

                <div class="col-md-3">
                    <strong>Item Name</strong><br>
                    {{ $stockCount->stock->item_name }}
                </div>

                <div class="col-md-3">
                    <strong>Brand</strong><br>
                    {{ $stockCount->stock->brand_name ?? '-' }}
                </div>

                <div class="col-md-3">
                    <strong>Size</strong><br>
                    {{ $stockCount->stock->size ?? '-' }}
                </div>
            </div>

            <hr>

            {{-- QUANTITY INFO --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">System Quantity</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $stockCount->stock->system_quantity }}"
                           disabled>
                </div>

                <div class="col-md-4">
                    <form method="POST"
      action="{{ route('admin.stock-counts.update', $stockCount->id) }}"
      onsubmit="return confirm('Are you sure you want to update this stock count?')">
                        @csrf
                        @method('PUT')

                        <label class="form-label">Entered Quantity</label>
                        <input type="number"
                               name="entered_quantity"
                               class="form-control"
                               value="{{ $stockCount->entered_quantity }}"
                               required>

                        <div class="mt-3">
                            <button class="btn btn-primary">
                                Update
                            </button>

                            <a href="{{ url()->previous() }}"
                               class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection