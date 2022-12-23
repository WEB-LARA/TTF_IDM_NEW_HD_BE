<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysMapSupplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'USER_ID',
        'SUPP_SITE_CODE',
        'BRANCH_CODE',
        'DATE',
        'STATUS'
    ];
}
