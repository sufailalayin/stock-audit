<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'barcode',
        'item_name',
        'brand_name',
        'size',
        'price',
        'system_quantity',
        'branch_id',
        'audit_file_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function auditFile()
    {
        return $this->belongsTo(AuditFile::class, 'audit_file_id');
    }


    // ✅ REQUIRED FOR AUDIT RESULT
    public function stockCounts()
    {
        return $this->hasMany(StockCount::class);
    }
}