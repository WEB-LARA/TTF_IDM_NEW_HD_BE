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
    protected $fillable = [
        'BPB_ID',
        'BPB_NUMBER',
        'BPB_DATE',
    ];

    public function getdata(){
        $data = testModel::get();
        return $data;
    }

    public function selectdata(){
        $data = testModel::where('BRANCH_CODE', '006')->take(10)->get();
        // ->orderBy('---')->...
        //->take(10)->...
        //->first(); //the first model matching the query constraints
        //->count(); //you may also use the count, sum, max, and other aggregate methods
        return $data;
    }

    public function getDataInquiryTtf(){
        $data = DB::leftjoin('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
              ->leftjoin('ttf_fp', 'ttf_fp.TTF_ID', '=', 'ttf_headers.TTF_ID')
            //   ->leftjoin('sys_supp_site', 'sys_supp_site.SUPP_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
              ->select('ttf_data_bpb.VENDOR_SITE_CODE',
              \DB::raw('(SELECT SUPP_NAME FROM sys_supplier WHERE sys_supplier.SUPP_ID = (SELECT 
                    SUPP_ID
                FROM
                    sys_supp_site b
                WHERE
                b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
                AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME'),'ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',
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
            ))
              ->get();
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
        return $data;
    }

    public function searchDataTtfbyFilter($branch, $nobpb, $tglbpb_from, $tglbpb_to, $nottf, $nofp, $session_id){
        $data = DB::select("SELECT
                ttf_data_bpb.VENDOR_SITE_CODE,
                (SELECT 
                    SUPP_NAME
                FROM
                    sys_supplier
                WHERE
                    SUPP_ID = (SELECT 
                                    SUPP_ID
                                FROM
                                    sys_supp_site b
                                WHERE
                                b.SUPP_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
                                AND b.SUPP_BRANCH_CODE = ttf_data_bpb.BRANCH_CODE)) AS SUPP_NAME,
                ttf_data_bpb.BPB_NUMBER,
                ttf_data_bpb.BPB_DATE,
                ttf_data_bpb.BPB_DPP,
                ttf_data_bpb.BPB_TAX,
                ttf_data_bpb.BPB_ID,
                ttf_fp.FP_NUM,
                ttf_fp.FP_DATE,
                ttf_fp.FP_DPP_AMT,
                ttf_fp.FP_TAX_AMT,
                ttf_headers.TTF_NUM,
                ttf_headers.TTF_DATE,
                ttf_headers.TTF_RETURN_DATE,
                (CASE
                    WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
                    WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
                    WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
                    WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
                    WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
                    WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
                END) AS STATUS_TTF
            FROM
                ttf_data_bpb
                    LEFT JOIN
                ttf_headers ON ttf_headers.VENDOR_SITE_CODE = ttf_data_bpb.VENDOR_SITE_CODE
                    LEFT JOIN
                ttf_fp ON ttf_fp.TTF_ID = ttf_headers.TTF_ID
            WHERE
                ttf_data_bpb.BRANCH_CODE = ?
            AND
                ttf_data_bpb.BPB_NUMBER = ?
            AND
                ttf_data_bpb.BPB_DATE BETWEEN ? AND ?
            AND
                ttf_headers.TTF_NUM = ?
            AND
                ttf_fp.FP_NUM = ?"
            ,[$branch,$nobpb,$tglbpb_from,$tglbpb_to,$nottf,$nofp]);
        // $data = testModel::join('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
        //       ->join('ttf_fp', 'ttf_fp.TTF_ID', '=', 'ttf_headers.TTF_ID')
        //       ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_fp.TTF_ID')
        //       ->where('ttf_data_bpb.BRANCH_CODE',$branch)
        //       ->orwhere('ttf_data_bpb.BPB_NUMBER',$nobpb)
        //       ->orwherebetween('ttf_data_bpb.BPB_DATE',[$tglbpb_from, $tglbpb_to])
        //       ->orwhere('ttf_headers.TTF_NUM',$nottf)
        //       ->orwhere('ttf_fp.FP_NUM',$nofp)
        //       ->select('ttf_data_bpb.VENDOR_SITE_CODE',
        //       'sys_supplier.SUPP_NAME','ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',\DB::raw(
        //         '( 
        //             CASE 
        //                  WHEN ttf_headers.TTF_STATUS = "" THEN "DRAFT"
        //                  WHEN ttf_headers.TTF_STATUS = "C" THEN "CANCEL"
        //                  WHEN ttf_headers.TTF_STATUS = "E" THEN "EXPIRED"
        //                  WHEN ttf_headers.TTF_STATUS = "R" THEN "REJECTED"
        //                  WHEN ttf_headers.TTF_STATUS = "S" THEN "SUBMITTED"
        //                  ELSE "VALIDATED"
        //             END
        //         ) AS STATUS_TTF'
        //     ))
        //       ->get();

              return $data;
    }
}