<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysSuppSite extends Model
{
    use HasFactory;
    protected $table = 'sys_supp_site';

    protected $primaryKey = 'SUPP_SITE_ID';
}
