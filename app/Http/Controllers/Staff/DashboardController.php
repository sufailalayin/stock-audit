<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditFile;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        // All files of branch
        $files = AuditFile::where('branch_id', $branchId)->get();

        // Counts
        $totalFiles     = $files->count();
        $pendingFiles   = $files->whereNull('completed_at')->count();
        $completedFiles = $files->whereNotNull('completed_at')->count();

        $mistakeFiles = AuditFile::where('branch_id', $branchId)
            ->whereHas('stockCounts', function ($q) {
                $q->where('difference', '!=', 0);
            })->count();

        return view('staff.dashboard', compact(
            'files',
            'totalFiles',
            'pendingFiles',
            'completedFiles',
            'mistakeFiles'
        ));
    }
}