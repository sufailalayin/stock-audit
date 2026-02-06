<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h3>Audit Report - {{ $auditFile->file_name }}</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Barcode</th>
            <th>Item</th>
            <th>System</th>
            <th>Counted</th>
            <th>Diff</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockCounts as $i => $c)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $c->stock->barcode ?? '' }}</td>
            <td>{{ $c->stock->item_name ?? '' }}</td>
            <td>{{ $c->stock->system_quantity ?? 0 }}</td>
            <td>{{ $c->entered_quantity }}</td>
            <td>{{ $c->difference }}</td>
            <td>{{ ucfirst($c->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>