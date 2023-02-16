<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class testModel4 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'sys_supp_site';
    protected $primaryKey = 'SUPP_SITE_ID';
    public $incrementing = false;
    public $timestamps = false;

    public function getDataSupplierbyBranch($branch){
        $data = testModel4::where('sys_supp_site.SUPP_BRANCH_CODE',$branch)
        ->select(\DB::raw("CONCAT('sys_supp_site.SUPP_SITE_CODE','-','sys_supp_site.SUPP_SITE_ALT_NAME') AS SUPPLIER"))
        // ->orderBy('sys_supp_site.SUPP_SITE_CODE','ASC')
        ->get();
        return $data;
    }
}