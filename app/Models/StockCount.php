<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    use HasFactory;

    protected $table = 'stock_counts';

    protected $fillable = [
        'stock_id',
        'audit_file_id',
        'user_id',
        'entered_quantity',
        'difference',
        'status',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function auditFile()
    {
        return $this->belongsTo(AuditFile::class);
    }
}