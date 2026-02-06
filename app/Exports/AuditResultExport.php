<?php

namespace App\Exports;

use App\Models\StockCount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditResultExport implements FromCollection, WithHeadings
{
    protected $auditFileId;

    public function __construct($auditFileId)
    {
        $this->auditFileId = $auditFileId;
    }

    public function collection()
    {
        return StockCount::with('stock')
            ->where('audit_file_id', $this->auditFileId)
            ->get()
            ->map(function ($c) {
                return [
                    'Barcode'   => $c->stock->barcode ?? '',
                    'Item'      => $c->stock->item_name ?? '',
                    'Brand'     => $c->stock->brand_name ?? '',
                    'Size'      => $c->stock->size ?? '',
                    'System Qty'=> $c->stock->system_quantity ?? 0,
                    'Counted'   => $c->entered_quantity,
                    'Difference'=> $c->difference,
                    'Status'    => ucfirst($c->status),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Barcode',
            'Item',
            'Brand',
            'Size',
            'System Qty',
            'Counted Qty',
            'Difference',
            'Status'
        ];
    }
}