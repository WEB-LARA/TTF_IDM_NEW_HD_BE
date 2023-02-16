<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class testModel3 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'sys_ref_branch';
    protected $primaryKey = 'BRANCH_ID';
    public $incrementing = false;
    public $timestamps = false;

    public function getDataBranch(){
            $data = testModel3::select(\DB::raw("CONCAT(sys_ref_branch.BRANCH_CODE,'-',sys_ref_branch.BRANCH_NAME) AS BRANCH"))
            ->orderBy('sys_ref_branch.BRANCH_CODE','ASC')
            ->get();
            return $data;
    }
}