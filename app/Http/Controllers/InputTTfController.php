<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;
use App\Models\TtfHeader;
use Illuminate\Support\Facades\DB;

class InputTTfController extends Controller
{
    //

    public function saveToTmpTtf(Request $request){

        $fp_type = $request->fp_type;
        $no_fp = $request->no_fp;
        if($no_fp == ""){
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
        $session_id = session()->getId();
        try{
            DB::transaction(function () use ($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag,$session_id){
                foreach($data_bpb as $a){
                    $tmpTable = TtfTmpTable::create([
                        'SEQ_NUM' => 1,
                        'FP_TYPE' => $fp_type,
                        'SUPP_SITE' => 'S73W',
                        'CABANG' => $branch_code,
                        'NO_FP' => $no_fp,
                        'NO_NPWP' => 'teest npwp',
                        'FP_DATE' => $fp_date,
                        'FP_DPP' => $dpp_fp,
                        'FP_TAX' => $tax_fp,
                        'BPB_NUM' => $a['bpb_num'],
                        'BPB_DATE' => $a['bpb_date'],
                        'BPB_AMOUNT' => $a['bpb_amount'],
                        'BPB_PPN' => $a['bpb_ppn'],
                        'SESS_ID' => $session_id,
                        'SCAN_FLAG' => $scan_flag
                    ]);
                }

            },5);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDataTtfTmpBYSessionId(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        
        $data = $ttf_tmp_table->getDataTtfTmpBYSessionId($request->supp_site_code);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    
    public function saveTTf(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $ttf_headers = new TtfHeader();
        $data = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->supp_site_code);
        
        // print_r($data);
            DB::transaction(function () use($data,$request){
                foreach($data as $a){
                    // print_r($a['FP_TYPE']);
                    $ttf_type = $a['FP_TYPE'];
                    $insertHeader = TtfHeader::create([
                        'BRANCH_CODE' => $a['CABANG'],
                        'VENDOR_SITE_CODE' => $a['SUPP_SITE'],
                        'TTF_NUM' => 'TES_NOMOR_TTF',
                        'TTF_DATE' => date('Y-m-d'),
                        'TTF_TYPE' => $ttf_type,
                        'TTF_STATUS' => 'D',
                        'SOURCE' => "WEB",
                        'CREATED_BY' => $request->user_id,
                        'CREATION_DATE' => date('Y-m-d')
                    ]);

                    $idHeader = $insertHeader->TTF_ID();

                    print_r($idHeader);
                }


            },5);
        // INSERT into ttf_headers(
        //                                 TTF_ID,
        //                                 BRANCH_CODE,
        //                                 VENDOR_SITE_CODE,
        //                                 TTF_NUM,
        //                                 TTF_DATE,
        //                                 TTF_TYPE,
        //                                 TTF_STATUS,
        //                                 SOURCE,
        //                                 CREATED_BY,
        //                                 CREATION_DATE,
        //                                 LAST_UPDATE_BY,
        //                                 LAST_UPDATE_DATE
        //                             )values(
        //                                 ?,
        //                                 ?,
        //                                 ?,
        //                                 ?,
        //                                 sysdate(),
        //                                 ?,
        //                                 ?,
        //                                 ?,
        //                                 ?,
        //                                 sysdate(),
        //                                 ?,
        //                                 sysdate()
        //                             )
    }
}
