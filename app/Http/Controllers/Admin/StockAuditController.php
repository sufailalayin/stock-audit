<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\AuditFile;
use App\Models\Stock;
use App\Models\StockCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\AuditResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StockAuditController extends Controller
{
      // ✅ EXCEL
    public function exportExcel(AuditFile $auditFile)
    {
        return Excel::download(
            new AuditResultExport($auditFile->id),
            'audit-'.$auditFile->id.'.xlsx'
        );
    }

    // ✅ PDF
    public function exportPdf(AuditFile $auditFile)
    {
        $stockCounts = StockCount::with('stock')
            ->where('audit_file_id', $auditFile->id)
            ->get();

        $pdf = Pdf::loadView('admin.stocks.export-pdf', compact(
            'auditFile',
            'stockCounts'
        ));

        return $pdf->download('audit-'.$auditFile->id.'.pdf');
    }

    public function destroyAuditFile(AuditFile $auditFile)
{
    // STEP 1: Get stock IDs BEFORE deleting stock_counts
    $stockIds = StockCount::where('audit_file_id', $auditFile->id)
        ->pluck('stock_id');

    // STEP 2: Delete stock counts
    $auditFile->stockCounts()->delete();

    // STEP 3: Delete related stocks
    Stock::whereIn('id', $stockIds)->delete();

    // STEP 4: Delete uploaded Excel file
    if ($auditFile->file_path && \Storage::exists($auditFile->file_path)) {
        \Storage::delete($auditFile->file_path);
    }

    // STEP 5: Delete audit file record
    $auditFile->delete();

    return back()->with('success', 'Audit file deleted successfully');
}
    public function update(Request $request, StockCount $stockCount)
{
    $request->validate([
        'entered_quantity' => 'required|integer|min:0',
    ]);

    $systemQty  = (int) $stockCount->stock->system_quantity;
    $enteredQty = (int) $request->entered_quantity;

    // DEFAULT
    $difference = 0;
    $status = 'pending';

    if ($enteredQty > 0) {
        $difference = $enteredQty - $systemQty;
        $status = ($difference === 0) ? 'matched' : 'mismatch';
    }

    $stockCount->update([
        'entered_quantity' => $enteredQty,
        'difference'       => $difference,
        'status'           => $status,
        'user_id'          => auth()->id(),
    ]);

    return redirect()
        ->route('admin.stocks.show', $stockCount->audit_file_id)
        ->with('success', 'Stock count updated successfully');
}
    // ===============================
    // SHOW AUDIT RESULT
    // ===============================
    public function show(AuditFile $auditFile)
    {
        $user = auth()->user();

if ($user->role === 'sub_admin' && !$user->branches->contains($auditFile->branch_id)) {
    abort(403);
}

        $stockCounts = StockCount::with('stock')
            ->where('audit_file_id', $auditFile->id)
            ->get();

        return view('admin.stocks.show', compact('auditFile', 'stockCounts'));
    }

    // ===============================
    // EDIT COUNT
    // ===============================
    public function edit(StockCount $stockCount)
    {
        $user = auth()->user();

if ($user->role === 'sub_admin' &&
    !$user->branches->contains($stockCount->stock->branch_id)) {
    abort(403);
}

        $stockCount->load('stock');
        return view('admin.stocks.edit-count', compact('stockCount'));
    }

    // ===============================
    // DELETE COUNT
    // ===============================
    public function destroy(StockCount $stockCount)
    {
        $user = auth()->user();

if ($user->role === 'sub_admin' &&
    !$user->branches->contains($stockCount->stock->branch_id)) {
    abort(403);
}
        $stockCount->delete();
        return back()->with('success', 'Stock count deleted successfully');
    }

    // ===============================
    // UPLOAD FORM
    // ===============================
    public function upload()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        $branches = Branch::all();
    } else {
        $branches = $user->branches;
    }

    return view('admin.stocks.upload', compact('branches'));
}

    // ===============================
    // HANDLE EXCEL UPLOAD
    // ===============================
    public function uploadStore(Request $request)
    {
        $request->validate([
            'file'      => 'required|mimes:xlsx,xls',
            'branch_id' => 'required'
        ]);

        // Store file
        $file = $request->file('file');
        $path = $file->store('audit_files');

        // Create audit file
        $auditFile = AuditFile::create([
            'branch_id'   => $request->branch_id,
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $path,
            'uploaded_by' => auth()->id(),
        ]);

        // Read Excel
        $rows = Excel::toCollection(null, $file)[0];

        foreach ($rows as $index => $row) {

            // 1️⃣ Skip header row
            if ($index === 0) {
                continue;
            }

            // 2️⃣ Read Excel columns
            // A=0 Sl No
            // B=1 Item Name
            // C=2 Brand
            // D=3 Size
            // E=4 SYSTEM QTY ✅
            // F=5 Barcode
            // G=6 MRP

            $itemName  = trim((string) ($row[1] ?? ''));
            $brand     = trim((string) ($row[2] ?? ''));
            $size      = trim((string) ($row[3] ?? ''));
            $systemQty = (int) ($row[4] ?? 0); // ✅ CORRECT VARIABLE
            $barcode   = strtoupper(trim((string) ($row[5] ?? '')));
            $mrp       = (float) ($row[6] ?? 0);

            if ($barcode === '') {
                continue;
            }

            // 3️⃣ Find or create stock
            $stock = Stock::where('barcode', $barcode)->first();

if ($stock) {

    // ✅ Barcode exists → ONLY update system qty + details
    $stock->update([
        'item_name'       => $itemName,
        'brand_name'      => $brand,
        'size'            => $size,
        'system_quantity' => $systemQty,
        'mrp'             => $mrp,
        'price'           => $mrp,
        'branch_id'       => $auditFile->branch_id,
    ]);

} else {

    // ✅ Barcode does not exist → create new stock
    $stock = Stock::create([
        'barcode'         => $barcode,
        'item_name'       => $itemName,
        'brand_name'      => $brand,
        'size'            => $size,
        'system_quantity' => $systemQty,
        'mrp'             => $mrp,
        'price'           => $mrp,
        'branch_id'       => $auditFile->branch_id,
    ]);
}
            // 4️⃣ Prevent duplicate stock count in same audit
            $exists = StockCount::where('audit_file_id', $auditFile->id)
                ->where('stock_id', $stock->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // 5️⃣ Create audit entry (PENDING ONLY)
            StockCount::create([
                'stock_id'         => $stock->id,
                'audit_file_id'    => $auditFile->id,
                'user_id'          => auth()->id(),
                'entered_quantity' => 0,   // staff not counted yet
                'difference'       => 0,   // MUST BE ZERO
                'status'           => 'pending',
            ]);
        }

        return redirect()
            ->route('admin.stocks.show', $auditFile->id)
            ->with('success', 'Audit uploaded successfully');
            }

}