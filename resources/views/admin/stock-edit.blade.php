@extends('layouts.admin')

@section('content')

<h2>Edit Stock</h2>

<form method="POST" action="/admin/stocks/update/{{ $stock->id }}">
    @csrf

    <label>Item Name</label><br>
    <input type="text" name="item_name" value="{{ $stock->item_name }}"><br><br>

    <label>Brand Name</label><br>
    <input type="text" name="brand_name" value="{{ $stock->brand_name }}"><br><br>

    <label>Size</label><br>
    <input type="text" name="size" value="{{ $stock->size }}"><br><br>

    <label>Color</label><br>
    <input type="text" name="color" value="{{ $stock->color }}"><br><br>

    <label>Price</label><br>
    <input type="number" name="price" value="{{ $stock->price }}"><br><br>

    <label>System Quantity</label><br>
    <input type="number" name="system_quantity" value="{{ $stock->system_quantity }}"><br><br>

    <button type="submit">Update Stock</button>
</form>

@endsection
