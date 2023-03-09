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

    public function searchDataInquiryLampiran($branch,$nottf,$kodesupp,$username,$tglttf_from,$tglttf_to,$status){
        $data = testModel2::join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_headers.BRANCH_CODE')
            //   ->leftjoin('sys_mapp_supp', 'sys_mapp_supp.SUPP_SITE_CODE', '=', 'ttf_headers.VENDOR_SITE_CODE')
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
            $data = $data->where('ttf_headers.CREATED_BY',$username);
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
        $skip = ($limit*$offset) - $limit;
        $data_count = $data->count();
        $data = $data->skip($skip)->take($limit)->get();
        $nomor = $skip+1;
        $return_data = array();
        $dataArray = array();
        $i=0;
        foreach ($data as $a){
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['TTF_ID'] = $a->TTF_ID;
            $dataArray[$i]['TTF_NUM'] = $a->TTF_NUM;
            $dataArray[$i]['STATUS_TTF'] = $a->STATUS_TTF;
            $dataArray[$i]['BRANCH_NAME'] = $a->BRANCH_NAME;
            $dataArray[$i]['CREATION_DATE'] = $a->CREATION_DATE;
            $dataArray[$i]['LAST_UPDATE_DATE'] = $a->LAST_UPDATE_DATE;
            $dataArray[$i]['JUMLAH_FP'] = $a->JUMLAH_FP;
            $dataArray[$i]['SUM_DPP_FP'] = $a->SUM_DPP_FP;
            $dataArray[$i]['SUM_TAX_FP'] = $a->SUM_TAX_FP;
            $dataArray[$i]['JUMLAH_BPB'] = $a->JUMLAH_BPB;
            $dataArray[$i]['SUM_DPP_BPB'] = $a->SUM_DPP_BPB;
            $dataArray[$i]['SUM_TAX_BPB'] = $a->SUM_TAX_BPB;
            $dataArray[$i]['SELISIH_DPP'] = $a->SELISIH_DPP;
            $dataArray[$i]['SELISIH_TAX'] = $a->SELISIH_TAX;
            $i++;
            $nomor++;
        }
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;

        return $return_data;
    }
}