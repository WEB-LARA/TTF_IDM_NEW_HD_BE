<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;
use Illuminate\Support\Facades\DB;

class InputTTfController extends Controller
{
    //

    public function saveToTmpTtf(Request $request){

        $fp_type = $request->fp_type;
        $no_fp = $request->no_fp;

        if($no_fp = ""){
            $no_fp = 0;
        }
        $supp_site_id = $request->supp_site_id;
        $branch_code = $request->branch_code;
        $fp_date = $request->fp_date;
        $dpp_fp = $request->dpp_fp;
        $tax_fp = $request->tax_fp;
        $data_bpb = $request->data_bpb;
        $scan_flag = $request->scan_flag;
        
        $ttf_tmp_table = new TtfTmpTable();

        $insert = $ttf_tmp_table->saveToTmpTable($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag);




    }
}
