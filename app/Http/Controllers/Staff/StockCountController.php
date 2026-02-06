<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditFile;
use App\Models\Stock;
use App\Models\StockCount;
use Illuminate\Http\Request;

class StockCountController extends Controller
{

   public function dashboard()
{
    $branchId = auth()->user()->branch_id;

    $files = \App\Models\AuditFile::with('stockCounts.stock')
        ->where('branch_id', $branchId)
        ->get();

    $mistakeFiles = $files->filter(function ($file) {
        return $file->stockCounts->where('difference', '!=', 0)->count() > 0;
    })->count();

    return view('staff.dashboard', [
        'files'           => $files,
        'totalFiles'      => $files->count(),
        'pendingFiles'    => $files->whereNull('completed_at')->count(),
        'mistakeFiles'    => $mistakeFiles,   // ✅ FIX
        'completedFiles'  => $files->whereNotNull('completed_at')->count(),
    ]);
}

    public function count(AuditFile $auditFile)
    {
        return view('staff.count', compact('auditFile'));
    }

    public function getStockByCode($code)
{
    $stock = Stock::where('barcode', $code)->first();

    if (!$stock) {
        return response()->json([
            'success' => false
        ], 404);
    }

    $count = StockCount::where('stock_id', $stock->id)
        ->where('audit_file_id', request()->auditFile?->id)
        ->first();

    return response()->json([
        'success' => true,
        'item' => [
            'id' => $stock->id,
            'name' => $stock->item_name,
            'brand' => $stock->brand_name,
            'size' => $stock->size,
            'system_qty' => $stock->system_quantity,
            'entered_quantity' => $count?->entered_quantity ?? 0
        ]
    ]);
}

public function store(Request $request, AuditFile $auditFile)
{
    $validated = $request->validate([
        'stock_id' => 'required|exists:stocks,id',
        'entered_quantity' => 'required|integer|min:0',
    ]);

    // 🔒 HARD LOCK: already counted
    $alreadyCounted = StockCount::where('audit_file_id', $auditFile->id)
        ->where('stock_id', $validated['stock_id'])
        ->where('status', '!=', 'pending') // ✅ THIS IS THE KEY
        ->first();

    if ($alreadyCounted) {
        return response()->json([
            'success' => false,
            'message' => '❌ This barcode is already counted'
        ], 409);
    }

    // Get stock
    $stock = Stock::findOrFail($validated['stock_id']);

    $systemQty  = (int) $stock->system_quantity;
    $enteredQty = (int) $validated['entered_quantity'];

    $difference = $enteredQty - $systemQty;
    $status = ($difference === 0) ? 'matched' : 'mismatch';

    // Save (only once)
    StockCount::where('audit_file_id', $auditFile->id)
        ->where('stock_id', $validated['stock_id'])
        ->update([
            'entered_quantity' => $enteredQty,
            'difference'       => $difference,
            'status'           => $status,
            'user_id'          => auth()->id(),
        ]);

    return response()->json([
        'success' => true
    ]);
}
}