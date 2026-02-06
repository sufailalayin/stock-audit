<?php

namespace App\Models;
use App\Models\Stock;
use App\Models\StockCount;
use Illuminate\Database\Eloquent\Model;

class AuditFile extends Model
{
    protected $fillable = [
    'file_name',
    'file_path',     // ✅ ADD THIS
    'branch_id',
    'uploaded_by',
    'total_items',
    'locked'
];

    /* =========================
       RELATIONSHIPS
    ========================== */

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function stockCounts()
{
    return $this->hasMany(StockCount::class);
}
    

}