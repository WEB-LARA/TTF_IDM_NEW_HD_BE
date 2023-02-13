<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use Ramsey\Uuid\Uuid; //UUIDs are universally unique alpha-numeric identifiers that are 36 characters long

class testModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'ttf_data_bpb'; //specify the model's table name
    protected $primaryKey = 'ID'; //The primary key associated with the table.
    public $incrementing = false; //Indicates if the model's ID is auto-incrementing.
    //protected $keyType = 'string'; // If your model's primary key is not an integer
    public $timestamps = false; //Indicates if the model should be timestamped.
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

    public function inquirydata(){
        $data = testModel::join('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
              ->join('ttf_fp', 'ttf_fp.TTF_ID', '=', 'ttf_headers.TTF_ID')
              ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_fp.TTF_ID')
              ->select('ttf_data_bpb.VENDOR_SITE_CODE',
              'sys_supplier.SUPP_NAME','ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',\DB::raw(
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
            ))
              ->take(10)
              ->get();

        return $data;
    }

    public function filterdata($branch, $nobpb, $tglbpb_from, $tglbpb_to, $nottf, $nofp, $session_id){
        $data = testModel::join('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'ttf_data_bpb.VENDOR_SITE_CODE')
              ->join('ttf_fp', 'ttf_fp.TTF_ID', '=', 'ttf_headers.TTF_ID')
              ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_fp.TTF_ID')
              ->where('ttf_headers.BRANCH_CODE',$branch)
              ->orwhere('ttf_data_bpb.BPB_NUMBER',$nobpb)
              ->orwherebetween('ttf_data_bpb.BPB_DATE',[$tglbpb_from, $tglbpb_to])
              ->orwhere('ttf_headers.TTF_NUM',$nottf)
              ->orwhere('ttf_fp.FP_NUM',$nofp)
              ->select('ttf_data_bpb.VENDOR_SITE_CODE',
              'sys_supplier.SUPP_NAME','ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE',\DB::raw(
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
            ))
              ->take(10)
              ->get();

              return $data;
    }

    public function inquirylampiran(){
        $data = testModel::join('ttf_headers', 'ttf_headers.BRANCH_CODE', '=', 'ttf_data_bpb.BRANCH_CODE')
                ->join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_data_bpb.BRANCH_CODE')
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
        $data = testModel::join('ttf_headers', 'ttf_headers.BRANCH_CODE', '=', 'ttf_data_bpb.BRANCH_CODE')
              ->join('sys_ref_branch', 'sys_ref_branch.BRANCH_CODE', '=', 'ttf_data_bpb.BRANCH_CODE')
              ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_data_bpb.ID')
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
              ->take(10)
              ->get();

              return $data;
    }

    // public function joindata(){
    //     $data = DB::table('ttf_data_bpb')
    //         ->crossJoin('ttf_lines')
    //         ->get();
    //     // $data = DB::table('ttf_data_bpb')
    //     //     ->join('ttf_lines', 'ttf_data_bpb.ID', '=', 'ttf_lines.TTF_BPB_ID')
    //     //     ->select('ttf_data_bpb.*', 'ttf_lines.CREATED_BY')
    //     //     ->get();
    //     // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.ID')
    //     //             ->get(); // berhasil tanpa data
    //     //$data = testModel::addSelect(['last_flight' => Flight::select('name')
    //     // ->whereColumn('destination_id', 'destinations.id')
    //     // ->orderByDesc('arrived_at')
    //     // ->limit(1)
    //     // ])->get();
    //     return $data;
    // }
    
}