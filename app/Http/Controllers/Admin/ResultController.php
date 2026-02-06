<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockCount;
use App\Models\Branch;
use App\Models\AuditFile;
use App\Models\Stock;


class ResultController extends Controller
{
    public function dashboard()
{
    $branches = Branch::with(['auditFiles' => function ($q) {
        $q->withCount([
            'stocks as total_items',
            'counts as counted_items',
            'counts as mismatch_items' => function ($q2) {
                $q2->where('status', 'red');
            }
        ])->with('stocks');
    }])->get();

    return view('admin.dashboard', compact('branches'));
}
    public function branchDashboard()
{
    $branches = \App\Models\Branch::withCount([
        'stocks',
        'stocks as audited_items' => function ($q) {
            $q->whereHas('counts');
        }
    ])->get();

    return view('admin.branch-dashboard', compact('branches'));
}

    public function staffPerformance()
   {
        $data = \App\Models\StockCount::selectRaw('
            user_id,
            COUNT(*) as total_count,
            SUM(status = "green") as matched,
            SUM(status = "red") as mismatched
        ')
        ->groupBy('user_id')
        ->with('user')
        ->get();

        return view('admin.staff-performance', compact('data'));
    }



    /* =========================
       SHOW AUDIT RESULTS
    ========================== */
    public function index(Request $request)
    {
        $query = StockCount::with(['stock.branch', 'user']);

        if ($request->branch_id) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        $results = $query->latest()->get();
        $branches = Branch::all();

        return view('admin.results', compact('results', 'branches'));
    }

    /* =========================
       EDIT AUDIT ENTRY
    ========================== */
    public function edit($id)
{
    $result = \App\Models\StockCount::with(['stock.branch','user'])
        ->findOrFail($id);

    return view('admin.result-edit', compact('result'));
}

    /* =========================
       UPDATE AUDIT ENTRY
    ========================== */
    public function update(Request $request, $id)
    {
        $request->validate([
            'entered_quantity' => 'required|integer|min:0',
        ]);

        $result = StockCount::findOrFail($id);

        $systemQty = $result->stock->system_quantity;
        $enteredQty = $request->entered_quantity;

        // Calculate difference & status
        $difference = $enteredQty - $systemQty;
        $status = ($difference === 0) ? 'green' : 'red';

        $result->update([
            'entered_quantity' => $enteredQty,
            'difference' => $difference,
            'status' => $status,
        ]);

        return redirect('/admin/results')->with('success', 'Audit entry updated successfully');
    }

    /* =========================
       DELETE AUDIT ENTRY
    ========================== */
    public function delete($id)
    {
        StockCount::findOrFail($id)->delete();
        return redirect('/admin/results')->with('success', 'Audit entry deleted successfully');
    }

    /* =========================
       EXPORT EXCEL (ALREADY USED)
    ========================== */
    public function export(Request $request)
    {
        // keep your existing export logic here
        abort(501); // placeholder if already implemented
    }

    /* =========================
       EXPORT PDF (ALREADY USED)
    ========================== */
    public function exportPdf(Request $request)
    {
        // keep your existing pdf logic here
        abort(501); // placeholder if already implemented
    }
    
    

    public function counting(AuditFile $auditFile)
    {
    // All entries for this file
      $entries = StockCount::with(['stock.brand'])
        ->where('audit_file_id', $auditFile->id)
        ->latest()
        ->get();

    // DASHBOARD (FILE-WISE)
      $totalItems   = $auditFile->stocks()->count();
      $counted      = $entries->count();
      $matched      = $entries->where('status', 'matched')->count();
      $mismatched   = $entries->where('status', 'mismatch')->count();
      $pending      = $totalItems - $counted;

       return view('admin.audit.counting', compact(
        'auditFile',
        'entries',
        'totalItems',
        'counted',
        'matched',
        'mismatched',
        'pending'
    ));
}


}
