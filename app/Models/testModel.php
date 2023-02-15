<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class testModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'ttf_data_bpb';
    protected $primaryKey = 'ID';
    public $incrementing = false; 
    public $timestamps = false;
    // protected $fillable = [
    //     'BPB_ID',
    //     'BPB_NUMBER',
    //     'BPB_DATE',
    // ];

    public function getdata(){
        $data = testModel::get();
        return $data;
    }

    public function selectdata(){
        $data = testModel::where('BRANCH_CODE', '006')->take(10)->get();
        return $data;
    }

    // public function getDataInquiryTtf(){
    //     $data = testModel::leftjoin('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
    //           ->leftjoin('ttf_fp', 'ttf_fp.TTF_ID', '=', 'ttf_headers.TTF_ID')
    //           ->select('ttf_data_bpb.VENDOR_SITE_CODE',
    //           \DB::raw('(SELECT SUPP_NAME FROM sys_supplier WHERE SUPP_ID = (SELECT 
    //                 SUPP_ID
    //             FROM
    //                 sys_supp_site b
    //             WHERE
    //             b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
    //             AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME'),
    //             'ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',
    //             \DB::raw(
    //             '( 
    //                 CASE 
    //                      WHEN ttf_headers.TTF_STATUS = "" THEN "DRAFT"
    //                      WHEN ttf_headers.TTF_STATUS = "C" THEN "CANCEL"
    //                      WHEN ttf_headers.TTF_STATUS = "E" THEN "EXPIRED"
    //                      WHEN ttf_headers.TTF_STATUS = "R" THEN "REJECTED"
    //                      WHEN ttf_headers.TTF_STATUS = "S" THEN "SUBMITTED"
    //                      WHEN ttf_headers.TTF_STATUS = "V" THEN "VALIDATED"
    //                 END
    //             ) AS STATUS_TTF'
    //         ))
    //         ->take(10)
    //         ->get();
        // $data = DB::select("SELECT
        //         ttf_data_bpb.VENDOR_SITE_CODE,
        //         (SELECT 
        //             SUPP_NAME
        //         FROM
        //             sys_supplier
        //         WHERE
        //             SUPP_ID = (SELECT 
        //                             SUPP_ID
        //                         FROM
        //                             sys_supp_site b
        //                         WHERE
        //                         b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
        //                         AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME,
        //         ttf_data_bpb.BPB_NUMBER,
        //         ttf_data_bpb.BPB_DATE,
        //         ttf_data_bpb.BPB_DPP,
        //         ttf_data_bpb.BPB_TAX,
        //         ttf_data_bpb.BPB_ID,
        //         ttf_fp.FP_NUM,
        //         ttf_fp.FP_DATE,
        //         ttf_fp.FP_DPP_AMT,
        //         ttf_fp.FP_TAX_AMT,
        //         ttf_headers.TTF_NUM,
        //         ttf_headers.TTF_DATE,
        //         ttf_headers.TTF_RETURN_DATE,
        //         (CASE
        //             WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
        //             WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
        //             WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
        //             WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
        //             WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
        //             WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
        //         END) AS STATUS_TTF
        //     FROM
        //         ttf_data_bpb
        //             LEFT JOIN
        //         ttf_headers ON ttf_headers.VENDOR_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
        //             LEFT JOIN
        //         ttf_fp ON ttf_fp.TTF_ID = ttf_headers.TTF_ID"
        //     );
    //     return $data;
    // }

    public function searchDataTtf($branch, $nobpb, $tglbpb_from, $tglbpb_to, $nottf, $nofp, $session_id){
        $data = testModel::leftjoin('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
              ->leftjoin('ttf_fp', 'ttf_fp.TTF_FP_ID', '=', 'ttf_lines.TTF_FP_ID')
              ->leftjoin('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_lines.TTF_ID')
              ->select('ttf_data_bpb.VENDOR_SITE_CODE',
              \DB::raw('(SELECT SUPP_NAME FROM sys_supplier WHERE SUPP_ID = (SELECT 
                    SUPP_ID
                FROM
                    sys_supp_site b
                WHERE
                b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
                AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME'),
                'ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',
                \DB::raw(
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
            ));
        if($branch){
            $data = $data->where('ttf_data_bpb.BRANCH_CODE',$branch);
        }
        if($nobpb){
            $data = $data->where('ttf_data_bpb.BPB_NUMBER',$nobpb);
        }
        if($tglbpb_from && $tglbpb_to){
            $data = $data->wherebetween('ttf_data_bpb.BPB_DATE',[$tglbpb_from, $tglbpb_to]);
        }
        if($nottf){
            $data = $data->where('ttf_headers.TTF_NUM',$nottf);
        }
        if($nofp){
            $data = $data->where('ttf_fp.FP_NUM',$nofp);
        }
        $data = $data->get();
        // $data = DB::select("SELECT
        //         ttf_data_bpb.VENDOR_SITE_CODE,
        //         (SELECT 
        //             SUPP_NAME
        //         FROM
        //             sys_supplier
        //         WHERE
        //             SUPP_ID = (SELECT 
        //                             SUPP_ID
        //                         FROM
        //                             sys_supp_site b
        //                         WHERE
        //                         b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
        //                         AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME,
        //         ttf_data_bpb.BPB_NUMBER,
        //         ttf_data_bpb.BPB_DATE,
        //         ttf_data_bpb.BPB_DPP,
        //         ttf_data_bpb.BPB_TAX,
        //         ttf_data_bpb.BPB_ID,
        //         ttf_fp.FP_NUM,
        //         ttf_fp.FP_DATE,
        //         ttf_fp.FP_DPP_AMT,
        //         ttf_fp.FP_TAX_AMT,
        //         ttf_headers.TTF_NUM,
        //         ttf_headers.TTF_DATE,
        //         ttf_headers.TTF_RETURN_DATE,
        //         (CASE
        //             WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
        //             WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
        //             WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
        //             WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
        //             WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
        //             WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
        //         END) AS STATUS_TTF
        //     FROM
        //         ttf_data_bpb
        //             LEFT JOIN
        //         ttf_headers ON ttf_headers.VENDOR_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
        //             LEFT JOIN
        //         ttf_fp ON ttf_fp.TTF_ID = ttf_headers.TTF_ID
        //     WHERE
        //         ttf_data_bpb.BRANCH_CODE = ?
        //     AND
        //         ttf_data_bpb.BPB_NUMBER = ?
        //     AND
        //         ttf_data_bpb.BPB_DATE BETWEEN ? AND ?
        //     AND
        //         ttf_headers.TTF_NUM = ?
        //     AND
        //         ttf_fp.FP_NUM = ?"
        //     ,[$branch,$nobpb,$tglbpb_from,$tglbpb_to,$nottf,$nofp]);
        
              return $data;
    }
}