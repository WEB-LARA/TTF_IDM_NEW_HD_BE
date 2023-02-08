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
use App\Models\SysFpFisik;
use App\Models\TtfLampiran;
use App\Models\TtfParamTable;
use Illuminate\Support\Facades\DB;
use File;
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
        $nama_file = $request->nama_file;
        $real_name = $request->real_name;
        try{
            DB::transaction(function () use ($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag,$session_id,$nama_file,$real_name){
                $sys_supp_site = new SysSuppSite();
                $dataSuppSite = $sys_supp_site->getSiteCodeAndNpwp($supp_site_id,$branch_code);
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
                // $sys_fp_fisik_temp = new SysFpFisikTemp();

                $createFpFisikTemp = SysFpFisikTemp::create([
                    "SESSION" => $session_id,
                    "FP_NUM" => $no_fp,
                    "FILENAME" => $nama_file,
                    "REAL_NAME" => $real_name,
                    "PATH_FILE" => public_path('file_temp_fp/'.$nama_file),
                    "CREATED_DATE" => date('Y-m-d')
                ]);

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
        $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->supp_site_code,$request->branch_code,$request->session_id);
        $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($request->supp_site_code,$request->branch_code,$request->session_id);
        $user_id = $request->user_id;
        // print_r($data);
        $concat_ttf_num = '';
        try{
            DB::transaction(function () use($dataHeader,$request,$user_id,$dataFpTmp,$ttf_tmp_table,$concat_ttf_num,$ttf_headers){
                foreach($dataHeader as $a){
                    $getTtfNumber = $this->getTtfNumber($a['CABANG']);
                    $ttf_type = $a['FP_TYPE'];
                    $insertHeader = TtfHeader::create([
                        'BRANCH_CODE' => $a['CABANG'],
                        'VENDOR_SITE_CODE' => $a['SUPP_SITE'],
                        'TTF_NUM' => $getTtfNumber,
                        'TTF_DATE' => date('Y-m-d'),
                        'TTF_TYPE' => $ttf_type,
                        'TTF_STATUS' => '',
                        'SOURCE' => "WEB",
                        'FLAG_GO' => $request->flag_go,
                        'FLAG_PPN' => $request->flag_ppn,
                        'SUM_DPP_FP' => $a['SUM_DPP_FP'],
                        'SUM_TAX_FP' => $a['SUM_TAX_FP'],
                        'CREATED_BY' => $user_id,
                        'CREATION_DATE' => date('Y-m-d')
                    ]);
                    $concat_ttf_num .= $getTtfNumber.',';
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
                        // Move File FP Fisik dari Temp Ke Folder Asli
                        $getPath = $this->moveFileTTfFromTemp($b['NO_FP'],$a['CABANG'],$getTtfNumber);
                        print_r("PATH DIR TTF=".$getPath['DIR_NO_TTF']);
                        echo "<br>";
                        print_r("PATH REAL FP=".$getPath['CONCAT_PATH']);
                        echo "<br>";
                        if($request->hasfile('file_lampiran'))
                        {
                            $this->saveLampiran($request->file_lampiran,$getPath['DIR_NO_TTF'],$idHeader);
                        }
                        // Delete SysFPFisikTemp
                        $sys_fp_fisik_temp = new SysFpFisikTemp();
                        $deleteTempFisik = $sys_fp_fisik_temp->deleteSysFpFisikBySessionAndFpNum($request->session_id,$b['NO_FP']);
                    }
                }
                $concat_ttf_num = rtrim($concat_ttf_num, ',');
                $updateHeaders = $ttf_headers->updateTtfInsert($concat_ttf_num);
                
            },5);
            return response()->json([
                    'status' => 'success',
                    'message' => 'TTF Berhasil Disimpan!',
                ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTtfNumber($branchCode){
        $ttf_param_table = new TtfParamTable();
        $getNumTTf = $ttf_param_table->getRunningYears();
        // print_r($getNumTTf->RUNNING_YEARS);
        // print_r($getNumTTf->YEAR_NOW);
        $running_year = $getNumTTf->RUNNING_YEARS;
        $year_now = $getNumTTf->YEAR_NOW;
        $counter_ttfs = $getNumTTf->COUNTER_TTFS;
        $year_use = $getNumTTf->YEAR_USE;
        if ($running_year != $year_now)
        {
            $updateRunningYears = $ttf_param_table->updateRunningYears($year_now);
        }
        $count = strlen($counter_ttfs);
        $ttf_num = $year_use . $branchCode . str_pad(($counter_ttfs) , $count, '0', STR_PAD_LEFT);

        $updateCounterTtfs = $ttf_param_table->updateCounterTtfs($counter_ttfs+1);

        return $ttf_num;
    }

    public function moveFileTTfFromTemp($no_fp,$cabang,$no_ttf){
        $sys_fp_fisik_temp = new SysFpFisikTemp();
        $getDataFpFisik = $sys_fp_fisik_temp->getDataSysFpFisikTmpByNoFp($no_fp);
        // Cek Folder Tahun
        $return_path = array();
        $year = date('Y');
        $dir = public_path('/file_djp_ttf_idm/'.$year);
        if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
            mkdir( $dir );     
            chmod($dir, 0777);
        }
        // Cek Folder Bulan
        $month = date('M');
        $dir_bulan = public_path('/file_djp_ttf_idm/'.$year.'/'.$month);
        if(!file_exists( $dir_bulan ) && !is_dir( $dir_bulan )){
            mkdir($dir_bulan);
            chmod($dir, 0777);
        }
        $dir_cabang = public_path('/file_djp_ttf_idm/'.$year.'/'.$month.'/'.$cabang);
        if(!file_exists( $dir_cabang ) && !is_dir( $dir_cabang )){
            mkdir($dir_cabang);
            chmod($dir, 0777);
        }
        $dir_no_ttf = public_path('/file_djp_ttf_idm/'.$year.'/'.$month.'/'.$cabang.'/'.$no_ttf);
        if(!file_exists( $dir_no_ttf ) && !is_dir( $dir_no_ttf )){
            mkdir($dir_no_ttf);
            chmod($dir, 0777);
        }
        $concatPath = $dir_no_ttf.'/'.$getDataFpFisik->FILENAME;
        File::move($getDataFpFisik->PATH_FILE, $concatPath);
        $return_path['DIR_NO_TTF'] = $dir_no_ttf;
        $return_path['CONCAT_PATH'] = $concatPath;
        return $return_path;
    }

    public function saveLampiran($file_lampiran,$path_simpan,$ttf_id){
        foreach($file_lampiran as $key => $file)
        {
            // $fileName = time().'.'.$file->extension();
            $fileName = $file->hashName();
            $real_name = $file->getClientOriginalName();
            $size = $file->getSize();
            // $request->file->move(public_path('/file_temp_fp'), $fileName)
            if($file->move($path_simpan, $fileName)){
                $sys_fp_fisik = new SysFpFisik();
                $insert = TtfLampiran::create([
                    "TTF_ID" => $ttf_id,
                    "REAL_NAME" => $real_name,
                    "PATH_FILE" => $path_simpan.'/'.$fileName,
                    "UPDATED_DATE" => date('Y-m-d H:i:s'),
                    "FILE_SIZE" =>$size
                ]);
            }
        }
    }
    public function insertToSysFpFisik($fp_num,$nama_file,$real_name){
        $sys_fp_fisik = new SysFpFisik();

        $insert = SysFpFisik::create([
            "FP_NUM" => $fp_num,
            "FILENAME" => $nama_file,
            "REAL_NAME" => $real_name,
            "PATH_FILE" => public_path('file_temp_fp/'.$nama_file),
            "CREATED_DATE" => date('Y-m-d')
        ]);

        if($insert){
            return 1;
        }else{
            return 0;
        }
    }
}
