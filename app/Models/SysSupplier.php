<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysSupplier extends Model
{
    use HasFactory;
    protected $table = 'sys_supplier';

    protected $primaryKey = 'SUPP_ID';

    protected $fillable = [
        'USER_ID',
        'SUPP_SITE_CODE',
        'BRANCH_CODE',
        'DATE',
        'STATUS'
    ];
}
