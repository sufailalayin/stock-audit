<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use App\Models\AuditFile;

class Branch extends Model
{
   protected $fillable = ['branch_name', 'location'];


   public function auditFiles()
   {
    return $this->hasMany(\App\Models\AuditFile::class);
   }


  public function stocks()
  {
   return $this->hasMany(Stock::class);
  }

}
