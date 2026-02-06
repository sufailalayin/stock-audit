<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\AuditFile;

class AuditFileController extends Controller
{
    public function index(Branch $branch)
    {
        $files = AuditFile::where('branch_id', $branch->id)->get();

        return view('admin.audit.files', compact('branch', 'files'));
    }
}