<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysRefBranch extends Model
{
    use HasFactory;
    protected $table = 'sys_ref_branch';

    protected $primaryKey = 'SUPP_SITE_ID';
}
