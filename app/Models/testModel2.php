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

    public function getDataInquiryLampiran(){
        $data = testModel2::leftjoin('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_headers.BRANCH_CODE')
                ->select('ttf_headers.TTF_NUM',\DB::raw(
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
                ),'sys_ref_branch.BRANCH_NAME','ttf_headers.CREATION_DATE','ttf_headers.LAST_UPDATE_DATE','ttf_headers.JUMLAH_FP','ttf_headers.SUM_DPP_FP','ttf_headers.SUM_TAX_FP','ttf_headers.JUMLAH_BPB','ttf_headers.SUM_DPP_BPB','ttf_headers.SUM_TAX_BPB','ttf_headers.SELISIH_DPP','ttf_headers.SELISIH_TAX')
              ->get();

        return $data;
    }

    public function searchDataInquiryLampiran($branch,$nottf,$kodesupp,$username,$tglttf_from,$tglttf_to,$status, $session_id){
        $data = testModel2::leftjoin('sys_mapp_supp', 'sys_mapp_supp.SUPP_SITE_CODE', '=', 'ttf_headers.VENDOR_SITE_CODE')
              ->leftjoin('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'sys_mapp_supp.BRANCH_CODE')
              ->select('ttf_headers.TTF_NUM',\DB::raw(
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
                    $data = $data->where('ttf_headers.TTF_STATUSS','!=', '');
                }
                $data = $data->get();

              return $data;
    }

    public function downloadInquiryLampiran(Request $request){
        $sys_fp_fisik = new SysFpFisik();
        $getDataFpFisik = $sys_fp_fisik->getDataByTtfNumber('230022473841');
        $ttf_lampiran = new TtfLampiran();
        $getDataTtfLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID(176);
        // $zip = new ZipArchive();
        $zip = new \ZipArchive();
        if ($zip->open(public_path('trigger_zip/test_new.zip'), \ZipArchive::CREATE) === TRUE)
        {
            foreach($getDataFpFisik as $a){
                $zip->addFile($a->PATH_FILE,$a->REAL_NAME);
            }
            foreach($getDataTtfLampiran as $b){
                $zip->addFile($b->PATH_FILE,$b->REAL_NAME);
            }
        }

        $zip->close();
        $file= public_path('trigger_zip/test_new.zip');
        $headers = array(
            "Content-type"        => "application/zip",
            "Content-Disposition" => "attachment; filename=test_zip.zip",
            "Content-Transfer-Encoding" => "Binary",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Response::download($file,'test_zip.zip' ,$headers);
        // header('Content-disposition: attachment; filename=download.zip');
        // header('Content-type: application/zip');
        // readfile(public_path('trigger_zip/test_new.zip'));
    }
}