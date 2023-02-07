<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;
use App\Models\TtfHeader;
use App\Models\TtfFp;
use App\Models\TtfLines;
use App\Models\SysSuppSite;
use App\Models\PrepopulatedFp;
use App\Models\TtfDataBpb;
use App\Models\SysFpFisikTemp;
use App\Models\TtfParamTable;
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
        $session_id = $request->session_id;
        $file = $request->file;
                $ttf_param_table = new TtfParamTable();
                $get_nomor_ttf = $ttf_param_table->getRunningYears();
                print_r("NOMOR_TTF");
                print_r($get_nomor_ttf);
        try{
            DB::transaction(function () use ($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag,$session_id,$file){
                $ttf_param_table = new TtfParamTable();
                $get_nomor_ttf = $ttf_param_table->getRunningYears();
                print_r("NOMOR_TTF");
                print_r($get_nomor_ttf);
                // $sys_supp_site = new SysSuppSite();
                // $dataSuppSite = $sys_supp_site->getSiteCodeAndNpwp($supp_site_id,$branch_code);
                // foreach($data_bpb as $a){
                //     $tmpTable = TtfTmpTable::create([
                //         'SEQ_NUM' => 1,
                //         'FP_TYPE' => $fp_type,
                //         'SUPP_SITE' => $dataSuppSite->SUPP_SITE_CODE,
                //         'CABANG' => $branch_code,
                //         'NO_FP' => $no_fp,
                //         'NO_NPWP' => $dataSuppSite->SUPP_PKP_NUM,
                //         'FP_DATE' => $fp_date,
                //         'FP_DPP' => $dpp_fp,
                //         'FP_TAX' => $tax_fp,
                //         'BPB_NUM' => $a['bpb_num'],
                //         'BPB_DATE' => $a['bpb_date'],
                //         'BPB_AMOUNT' => $a['bpb_dpp'],
                //         'BPB_PPN' => $a['bpb_ppn'],
                //         'SESS_ID' => $session_id,
                //         'SCAN_FLAG' => $scan_flag
                //     ]);
                // }
                // // $sys_fp_fisik_temp = new SysFpFisikTemp();
                // $fileNameConverted = time().'.'.'pdf';
                // $real_name = $file->getClientOriginalName();
                // if($file->move(public_path('/file_temp_fp'), $fileNameConverted)){
                //     $createFpFisikTemp = SysFpFisikTemp::create([
                //         "SESSION" => $session_id,
                //         "FP_NUM" => $no_fp,
                //         "FILENAME" => $fileNameConverted,
                //         "REAL_NAME" => $real_name,
                //         "PATH_FILE" => public_path('file_temp_fp/'.$fileNameConverted)
                //     ]);
                // }

            },5);

            return response()->json([
                'status' => 'success',
                'message' => 'sukses'
            ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editTmpTTF(Request $request){
        $no_fp_lama = $request->no_fp_lama;
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
        $session_id = $request->session_id;
        $ttf_tmp_table = new TtfTmpTable();

        try{
            DB::transaction(function () use ($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag,$session_id,$no_fp_lama){
                $sys_supp_site = new SysSuppSite();
                $dataSuppSite = $sys_supp_site->getSiteCodeAndNpwp($supp_site_id,$branch_code);
                $deleteTmpTable = TtfTmpTable::where('NO_FP',$no_fp_lama)->delete();
                foreach($data_bpb as $a){
                    $tmpTable = TtfTmpTable::create([
                        'SEQ_NUM' => 1,
                        'FP_TYPE' => $fp_type,
                        'SUPP_SITE' => $dataSuppSite->SUPP_SITE_CODE,
                        'CABANG' => $branch_code,
                        'NO_FP' => $no_fp,
                        'NO_NPWP' => $dataSuppSite->SUPP_PKP_NUM,
                        'FP_DATE' => $fp_date,
                        'FP_DPP' => $dpp_fp,
                        'FP_TAX' => $tax_fp,
                        'BPB_NUM' => $a['bpb_num'],
                        'BPB_DATE' => $a['bpb_date'],
                        'BPB_AMOUNT' => $a['bpb_dpp'],
                        'BPB_PPN' => $a['bpb_ppn'],
                        'SESS_ID' => $session_id,
                        'SCAN_FLAG' => $scan_flag
                    ]);
                }

            },5);

            return response()->json([
                'status' => 'success',
                'message' => 'sukses'
            ]);
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
        $ttf_fp = new TtfFp();
        $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->supp_site_code,$request->branch_code);
        $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($request->supp_site_code,$request->branch_code);
        $user_id = $request->user_id;
        // print_r($data);
        try{
            DB::transaction(function () use($dataHeader,$request,$user_id,$dataFpTmp,$ttf_tmp_table){
                foreach($dataHeader as $a){
                    // print_r($a['FP_TYPE']);
                    $ttf_type = $a['FP_TYPE'];
                    $insertHeader = TtfHeader::create([
                        'BRANCH_CODE' => $a['CABANG'],
                        'VENDOR_SITE_CODE' => $a['SUPP_SITE'],
                        'TTF_NUM' => 'TES_NOMOR_TTF',
                        'TTF_DATE' => date('Y-m-d'),
                        'TTF_TYPE' => $ttf_type,
                        'TTF_STATUS' => '',
                        'SOURCE' => "WEB",
                        'FLAG_GO' => $request->flag_go,
                        'FLAG_PPN' => $request->flag_ppn,
                        'CREATED_BY' => $user_id,
                        'CREATION_DATE' => date('Y-m-d')
                    ]);

                    $idHeader = $insertHeader->TTF_ID;

                    foreach($dataFpTmp as $b){
                        $insertFp = TtfFp::create([
                            'TTF_ID' => $idHeader,
                            'FP_NUM' => $b['NO_FP'],
                            'FP_TYPE' => $b['FP_TYPE'],
                            'FP_DATE' => $b['FP_DATE'],
                            'FP_DPP_AMT' => $b['FP_DPP'],
                            'FP_TAX_AMT' => $b['FP_TAX'],
                            'USED_FLAG' => "Y",
                            'CREATED_BY' => $user_id,
                            'CREATION_DATE' => date('Y-m-d'),
                            'TTF_HEADERS_TTF_ID' => $idHeader,
                            'SCAN_FLAG' => $b['SCAN_FLAG']
                        ]);
                        $idFp = $insertFp->TTF_FP_ID;
                        $getDataBPBperFP = $ttf_tmp_table->getDataTTFTmpBPB($request->supp_site_code,$request->branch_code,$b['NO_FP']);

                        foreach ($getDataBPBperFP as $c){
                            $insertLines = TtfLines::create([
                                'TTF_ID' => $idHeader,
                                'TTF_BPB_ID' => $c['BPB_ID'],
                                'TTF_FP_ID' => $idFp,
                                'ACTIVE_FLAG' => "Y",
                                'CREATION_DATE' => date('Y-m-d'),
                                'CREATED_BY' => $user_id,
                                'TTF_HEADERS_TTF_ID' => $idHeader,
                                'TTF_FP_TTF_FP_ID' => $idFp
                            ]);

                            $ttf_data_bpb = new TtfDataBpb();

                            $updateDataBpb = $ttf_data_bpb->updateDataBpb($c['BPB_ID'],'Y');
                        }
                        $prepopulated_fp = new PrepopulatedFp();
                        $updatePrepopulated = $prepopulated_fp->updatePrepopulatedFP($b['NO_FP'],'Y');
                    }
                }

            },5);
            return response()->json([
                    'status' => 'success',
                    'message' => 'TTF Berhasil Disimpan!',
                ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
