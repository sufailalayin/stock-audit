<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class DashboardController extends Controller
{
    public function index()
    {
        $branches = Branch::with([
            'auditFiles.stockCounts.stock'
        ])->get();

        foreach ($branches as $branch) {
            foreach ($branch->auditFiles as $file) {

                // TOTAL ITEMS = how many stockCounts created from upload
                $file->total_items = $file->stockCounts->count();

                // COUNTED ITEMS
                $file->counted_items = $file->stockCounts
                    ->where('entered_quantity', '>', 0)
                    ->count();

                // MISMATCH ITEMS
                $file->mismatch_items = $file->stockCounts
                    ->where('status', 'mismatch')
                    ->count();

                // PENDING ITEMS
                $file->pending_items = max(
                    $file->total_items - $file->counted_items,
                    0
                );

                // LOCKED STATUS
                $file->locked = ($file->pending_items === 0);

            
            }
        }

        return view('admin.dashboard', compact('branches'));
    }

}