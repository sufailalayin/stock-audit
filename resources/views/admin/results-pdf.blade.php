<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .green { background: #d4edda; }
        .red { background: #f8d7da; }
    </style>
</head>
<body>

<h2>Stock Audit Report</h2>

<table>
    <tr>
        <th>Branch</th>
        <th>Staff</th>
        <th>QR Code</th>
        <th>Item</th>
        <th>Entered Qty</th>
        <th>Status</th>
        <th>Difference</th>
        <th>Date</th>
    </tr>

    @foreach($results as $row)
        <tr class="{{ $row->status }}">
            <td>{{ $row->stock->branch->branch_name ?? '' }}</td>
            <td>{{ $row->user->name }}</td>
            <td>{{ $row->stock->barcode }}</td>
            <td>{{ $row->stock->item_name }}</td>
            <td>{{ $row->entered_quantity }}</td>
            <td>{{ strtoupper($row->status) }}</td>
            <td>{{ $row->difference }}</td>
            <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>
