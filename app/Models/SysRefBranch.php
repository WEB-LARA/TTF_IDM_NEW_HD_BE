<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SysRefBranch extends Model
{
    use HasFactory;
    protected $table = 'sys_ref_branch';

    protected $primaryKey = 'SUPP_SITE_ID';

    public function getAllbranch(){
        $data = SysRefBranch::select('BRANCH_CODE',\DB::raw('CONCAT(BRANCH_CODE,\'-\',BRANCH_NAME)'))->get();

        return $data;
    }
}

