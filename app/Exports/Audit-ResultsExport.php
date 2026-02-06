<?php

namespace App\Exports;

use App\Models\StockCount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditResultsExport implements FromCollection, WithHeadings
{
    protected $branchId;

    public function __construct($branchId = null)
    {
        $this->branchId = $branchId;
    }

    public function collection()
    {
        $query = StockCount::with(['stock.branch', 'user']);

        if ($this->branchId) {
            $query->whereHas('stock', function ($q) {
                $q->where('branch_id', $this->branchId);
            });
        }

        return $query->get()->map(function ($row) {
            return [
                'Branch' => $row->stock->branch->branch_name ?? '',
                'Staff' => $row->user->name,
                'QR Code' => $row->stock->barcode,
                'Item Name' => $row->stock->item_name,
                'Entered Qty' => $row->entered_quantity,
                'Status' => strtoupper($row->status),
                'Difference' => $row->difference,
                'Date Time' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Branch',
            'Staff',
            'QR Code',
            'Item Name',
            'Entered Qty',
            'Status',
            'Difference',
            'Date Time'
        ];
    }
}
