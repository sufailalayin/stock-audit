<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\AuditFile;
use App\Imports\StocksImport;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function files(Branch $branch)
{
    $user = auth()->user();

if ($user->role === 'sub_admin' && !$user->branches->contains($branch->id)) {
    abort(403);
}

    $files = AuditFile::where('branch_id', $branch->id)
        ->latest()
        ->get();

    return view('admin.stocks.files', compact('branch', 'files'));
}

    // =========================
    // UPLOAD
    // =========================
    public function uploadForm()
    {
        $branches = Branch::all();
        return view('admin.stocks.upload_excel', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $auditFile = AuditFile::create([
            'file_name' => $request->file->getClientOriginalName(),
            'branch_id' => $request->branch_id,
            'uploaded_by' => auth()->id()
        ]);

        Excel::import(
            new StocksImport($request->branch_id, $auditFile->id),
            $request->file('file')
        );

        $auditFile->update([
            'total_items' => Stock::where('audit_file_id', $auditFile->id)->count()
        ]);

        return back()->with('success', 'Audit file uploaded');
    }

    // =========================
    // STOCK LIST
    // =========================
    public function list()
    {
        $stocks = Stock::with('branch')->latest()->get();
        return view('admin.stock-list', compact('stocks'));
    }

    // =========================
    // EDIT STOCK (ENTERED QTY)
    // =========================
    public function edit(Stock $stock)
    {
        return view('admin.stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'entered_quantity' => 'required|integer|min:0'
        ]);

        $difference = $request->entered_quantity - $stock->system_quantity;

        $status = $difference == 0 ? 'matched' : 'mismatch';

        $stock->stockCounts()->updateOrCreate(
            ['stock_id' => $stock->id],
            [
                'entered_quantity' => $request->entered_quantity,
                'difference' => $difference,
                'status' => $status
            ]
        );

        return redirect()->back()->with('success', 'Stock updated');
    }

    // =========================
    // DELETE STOCK
    // =========================
    public function delete(Stock $stock)
    {
        $stock->stockCounts()->delete();
        $stock->delete();

        return redirect()->back()->with('success', 'Stock deleted');
    }

    // =========================
    // BRANCHES
    // =========================
    public function branches()
    {
        $branches = Branch::withCount('stocks')->get();
        return view('admin.stocks.branches', compact('branches'));
    }
    


}