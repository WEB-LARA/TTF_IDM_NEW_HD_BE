<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class testModel2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'ttf_headers';
    protected $primaryKey = 'TTD_ID';
    public $incrementing = false;
    public $timestamps = false;

    public function inquirylampiran(){
        $data = testModel2::join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_headers.BRANCH_CODE')
                ->select('ttf_headers.TTF_NUM',\DB::raw(
                '( 
                    CASE 
                         WHEN ttf_headers.TTF_STATUS = "" THEN "DRAFT"
                         WHEN ttf_headers.TTF_STATUS = "C" THEN "CANCEL"
                         WHEN ttf_headers.TTF_STATUS = "E" THEN "EXPIRED"
                         WHEN ttf_headers.TTF_STATUS = "R" THEN "REJECTED"
                         WHEN ttf_headers.TTF_STATUS = "S" THEN "SUBMITTED"
                         ELSE "VALIDATED"
                    END
                ) AS STATUS_TTF'
                ),'sys_ref_branch.BRANCH_NAME','ttf_headers.CREATION_DATE','ttf_headers.LAST_UPDATE_DATE','ttf_headers.JUMLAH_FP','ttf_headers.SUM_DPP_FP','ttf_headers.SUM_TAX_FP','ttf_headers.JUMLAH_BPB','ttf_headers.SUM_DPP_BPB','ttf_headers.SUM_TAX_BPB')
              ->take(10)
              ->get();

        return $data;
    }

    public function filterlampiran($branch,$nottf,$kodesupp,$username,$tglttf_from,$tglttf_to,$status, $session_id){
        $data = testModel2::join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_headers.BRANCH_CODE')
              ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_headers.TTF_ID')
              ->join('sys_user', 'sys_user.ID_USER', '=', 'ttf_headers.CREATED_BY')
              ->where('ttf_headers.BRANCH_CODE',$branch)
              ->orwhere('ttf_headers.TTF_NUM',$nottf)
              ->orwhere('sys_supplier.SUPP_CODE',$kodesupp)
              ->orwhere('sys_user.USERNAME',$username)
              ->orwherebetween('ttf_headers.CREATION_DATE',[$tglttf_from, $tglttf_to])
              ->orwhere('ttf_headers.TTF_STATUS',$status)
              ->select('ttf_headers.TTF_NUM',\DB::raw(
                '( 
                    CASE 
                         WHEN ttf_headers.TTF_STATUS = "" THEN "DRAFT"
                         WHEN ttf_headers.TTF_STATUS = "C" THEN "CANCEL"
                         WHEN ttf_headers.TTF_STATUS = "E" THEN "EXPIRED"
                         WHEN ttf_headers.TTF_STATUS = "R" THEN "REJECTED"
                         WHEN ttf_headers.TTF_STATUS = "S" THEN "SUBMITTED"
                         ELSE "VALIDATED"
                    END
                ) AS STATUS_TTF'
                ),'sys_ref_branch.BRANCH_NAME','ttf_headers.CREATION_DATE','ttf_headers.LAST_UPDATE_DATE','ttf_headers.JUMLAH_FP','ttf_headers.SUM_DPP_FP','ttf_headers.SUM_TAX_FP','ttf_headers.JUMLAH_BPB','ttf_headers.SUM_DPP_BPB','ttf_headers.SUM_TAX_BPB')
              ->get();

              return $data;
    }
}