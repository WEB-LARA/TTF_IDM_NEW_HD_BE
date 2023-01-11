<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;

class TtfTmpTableController extends Controller
{
    public function getDataTmpTtfBySuppCodeAndBranch(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $data = $ttf_tmp_table->getDataTmpTtfBySuppCodeAndBranch($request->supp_site_code,$request->branch_code);
        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function getDataDetailBPBperFP(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $data = $ttf_tmp_table->getDataDetailBPBperFP($request->supp_site_code,$request->branch_code,$request->no_fp);
        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function deleteTmpTableBySiteCodeAndBranch(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $data = $ttf_tmp_table->deleteTmpTableBySiteCodeAndBranch($request->supp_site_code,$request->branch_code);

        if($data){
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data TTF berhasil dihapus',
                ]);
        }else{
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data TTF gagal dihapus',
                ]);
        }
    }
}
