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
    protected $primaryKey = 'TTF_ID';
    public $incrementing = false;
    public $timestamps = false;

    public function searchDataInquiryLampiran($branch,$nottf,$kodesupp,$username,$tglttf_from,$tglttf_to,$status, $session_id){
        $data = testModel2::join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_headers.BRANCH_CODE')
              ->leftjoin('sys_mapp_supp', 'sys_mapp_supp.SUPP_SITE_CODE', '=', 'ttf_headers.VENDOR_SITE_CODE')
            //   ->where('ttf_headers.BRANCH_CODE','=','sys_mapp_supp.BRANCH_CODE')
              ->select('ttf_headers.TTF_ID','ttf_headers.TTF_NUM',\DB::raw(
                '( 
                    CASE 
                         WHEN ttf_headers.TTF_STATUS = "" THEN "DRAFT"
                         WHEN ttf_headers.TTF_STATUS = "C" THEN "CANCEL"
                         WHEN ttf_headers.TTF_STATUS = "E" THEN "EXPIRED"
                         WHEN ttf_headers.TTF_STATUS = "R" THEN "REJECTED"
                         WHEN ttf_headers.TTF_STATUS = "S" THEN "SUBMITTED"
                         WHEN ttf_headers.TTF_STATUS = "V" THEN "VALIDATED"
                    END
                ) AS STATUS_TTF'
                ),'sys_ref_branch.BRANCH_NAME','ttf_headers.CREATION_DATE','ttf_headers.LAST_UPDATE_DATE','ttf_headers.JUMLAH_FP','ttf_headers.SUM_DPP_FP','ttf_headers.SUM_TAX_FP','ttf_headers.JUMLAH_BPB','ttf_headers.SUM_DPP_BPB','ttf_headers.SUM_TAX_BPB','ttf_headers.SELISIH_DPP','ttf_headers.SELISIH_TAX');
                if($branch){
                    $data = $data->where('ttf_headers.BRANCH_CODE',$branch);
                }
                if($nottf){
                    $data = $data->where('ttf_headers.TTF_NUM',$nottf);
                }
                if($kodesupp){
                    $data = $data->where('ttf_headers.VENDOR_SITE_CODE',$kodesupp);
                }
                if($username){
                    $data = $data->where('sys_mapp_supp.USER_ID',$username);
                }
                if($tglttf_from && $tglttf_to){
                    $data = $data->wherebetween('ttf_headers.TTF_DATE',[$tglttf_from, $tglttf_to]);
                }
                if($status){
                    $data = $data->where('ttf_headers.TTF_STATUS',$status);
                }
                elseif($status == ''){
                    $data = $data->where('ttf_headers.TTF_STATUS','!=', '');
                }
                $data = $data->get();

              return $data;
    }
}