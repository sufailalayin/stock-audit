<?php

namespace App\Imports;

use App\Models\Stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StocksImport implements ToCollection
{
    protected $branchId;
    protected $auditFileId;

    public function __construct($branchId, $auditFileId)
    {
        $this->branchId = $branchId;
        $this->auditFileId = $auditFileId;
    }

    public function collection(Collection $rows)
    {
    
        
        foreach ($rows as $index => $row) {

            // Skip header
            if ($index === 0) {
                continue;
            }

            // Skip empty item rows
            if (empty($row[1])) {
                continue;
            }

            Stock::create([
                'branch_id'       => $this->branchId,
                'audit_file_id'   => $this->auditFileId,

                'item_name'       => trim($row[1]),   // Column B
                'brand_name'      => $row[2] ?? null, // Column C
                'size'            => $row[3] ?? null, // Column D
                'system_quantity' => (int) $row[4],   // Column E
                'barcode'         => $row[5] ?? null, // Column F
                'price'           => $row[6] ?? 0,    // Column G
            ]);
        }
    }
}
