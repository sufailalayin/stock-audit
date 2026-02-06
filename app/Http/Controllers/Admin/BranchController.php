<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\AuditFile;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('id', 'desc')->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255'
        ]);

        Branch::create([
            'branch_name' => $request->branch_name
        ]);

        return redirect()->back()->with('success', 'Branch created successfully');
    }
    

    public function files(Branch $branch)
{
        $files = AuditFile::where('branch_id', $branch->id)
                ->latest()
                ->get();

        return view('admin.branches.files', compact('branch', 'files'));
}

}