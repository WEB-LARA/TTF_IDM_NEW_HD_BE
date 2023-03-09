<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class testModel extends Model
{
    use HasFactory;
    // WEBDEV2
    // protected $connection = 'mysql2';
    // WEBDEV1
    protected $connection = 'mysql';
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

    public function searchDataTtf($branch, $nobpb, $tglbpb_from, $tglbpb_to, $nottf, $nofp, $offset,$limit){
        $data = testModel::leftjoin('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
              ->leftjoin('ttf_fp', 'ttf_fp.TTF_FP_ID', '=', 'ttf_lines.TTF_FP_ID')
              ->leftjoin('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_lines.TTF_ID')
              ->select('ttf_data_bpb.VENDOR_SITE_CODE'.
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
        else{
            $now = Carbon::now()->format('Y-m-d');
            $sub = Carbon::now()->subDays(100)->format('Y-m-d');
            $data = $data->wherebetween('ttf_data_bpb.BPB_DATE',[$sub, $now]);
        }
        if($nottf){
            $data = $data->where('ttf_headers.TTF_NUM',$nottf);
        }
        if($nofp){
            $data = $data->where('ttf_fp.FP_NUM',$nofp);
        }
        // $data = $data->get();
        $skip = ($limit*$offset) - $limit;
        $data_count = $data->count();
        $data = $data->skip($skip)->take($limit)->get();
        $nomor = $skip+1;
        $return_data = array();
        $dataArray = array();
        $i=0;
        foreach ($data as $a){
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['VENDOR_SITE_ID'] = $a->VENDOR_SITE_ID;
            $dataArray[$i]['SUPP_NAME'] = $a->SUPP_NAME;
            $dataArray[$i]['BPB_NUMBER'] = $a->BPB_NUMBER;
            $dataArray[$i]['BPB_DATE'] = $a->BPB_DATE;
            $dataArray[$i]['BPB_DPP'] = $a->BPB_DPP;
            $dataArray[$i]['BPB_TAX'] = $a->BPB_TAX;
            $dataArray[$i]['FP_NUM'] = $a->FP_NUM;
            $dataArray[$i]['FP_DATE'] = $a->FP_DATE;
            $dataArray[$i]['FP_DPP_AMT'] = $a->FP_DPP_AMT;
            $dataArray[$i]['FP_TAX_AMT'] = $a->FP_TAX_AMT;
            $dataArray[$i]['TTF_NUM'] = $a->TTF_NUM;
            $dataArray[$i]['TTF_DATE'] = $a->TTF_DATE;
            $dataArray[$i]['TTF_RETURN_DATE'] = $a->TTF_RETURN_DATE;
            $dataArray[$i]['STATUS_TTF'] = $a->STATUS_TTF;
            $i++;
            $nomor++;
        }
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;

        return $return_data;
    }
        // print_r($data);        // $data = DB::select("SELECT
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

        public function reportTtfs($id, $branch){
            $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
                    ->join('ttf_fp', 'ttf_fp.TTF_FP_ID', '=', 'ttf_lines.TTF_FP_ID')
                    ->join('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_lines.TTF_ID')
                    ->whereNotNull('ttf_fp.TTF_ID')
                    ->whereNotNull('ttf_fp.FP_NUM')
                    ->select('ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_fp.FP_NUM','ttf_fp.FP_DATE',
                    \DB::raw('(ttf_data_bpb.BPB_DPP + ttf_data_bpb.BPB_TAX) AS NILAI_TTF'));
            if($id){
                    $data = $data->where('ttf_fp.TTF_ID',$id);
                }
            if($branch){
                $data = $data->where('ttf_headers.BRANCH_CODE',$branch);
            }
            $data = $data->get();
            return $data;
        }
}