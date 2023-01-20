<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;

class TtfTmpTableController extends Controller
{
    public function getDataTmpTtfBySessId(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $data = $ttf_tmp_table->getDataTmpTtfBySessId($request->session_id);
        $dataArray = array();
        $i = 0;
        foreach ($data as $a){
            // print_r($a->FP_TYPE);
            $dataLines = $ttf_tmp_table->getDataDetailBPBperFP($request->supp_site_code,$request->branch_code,$a->NO_FP,$request->session_id);
            $dataArray[$i]['ID'] = $a->ID;
            $dataArray[$i]['FP_TYPE'] = $a->FP_TYPE;
            $dataArray[$i]['NO_FP'] = $a->NO_FP;
            $dataArray[$i]['TANGGAL_FP'] = $a->TANGGAL_FP;
            $dataArray[$i]['FP_DPP'] = $a->FP_DPP;
            $dataArray[$i]['FP_TAX'] = $a->FP_TAX;
            $dataArray[$i]['JUMLAH_BPB'] = $a->JUMLAH_BPB;
            $dataArray[$i]['JUMLAH_DPP_BPB'] = $a->JUMLAH_DPP_BPB;
            $dataArray[$i]['JUMLAH_PPN_BPB'] = $a->JUMLAH_PPN_BPB;
            $dataArray[$i]['SEL_DPP'] = $a->SEL_DPP;
            $dataArray[$i]['SEL_PPN'] = $a->SEL_PPN;
            $dataArray[$i]['DATA_LINES'] = $dataLines;
            $i++;
        }

        // print_r($dataArray);
        return response()->json([
                'status' => 'success',
                'data' => $dataArray,
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
        $data = $ttf_tmp_table->deleteTmpTableBySiteCodeAndBranch($request->session_id,$request->supp_site_code,$request->branch_code);

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

    public function deleteTmpTableByNoFpAndSessId(Request $request){
        $ttf_tmp_table = new TtfTmpTable();

        $delete = $ttf_tmp_table->deleteTmpTableByNoFpAndSessId($request->no_fp,$request->session_id);

        if($delete){
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
