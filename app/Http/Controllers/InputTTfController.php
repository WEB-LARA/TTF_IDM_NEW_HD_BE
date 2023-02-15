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
use App\Models\TtfUploadTmp;
use App\Models\SysMapSupplier;
use App\Models\TempUploadDjpCsv;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ConvertImageController;
use File;
use Response;
use Smalot\PdfParser\Parser;
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
        // $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->supp_site_code,$request->branch_code,$request->session_id);
        // $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($request->supp_site_code,$request->branch_code,$request->session_id);
        $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->session_id);
        $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($request->session_id);
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
                        $getDataBPBperFP = $ttf_tmp_table->getDataTTFTmpBPB($a['SUPP_SITE'],$a['CABANG'],$b['NO_FP']);
                        // $getDataBPBperFP = $ttf_tmp_table->getDataTTFTmpBPB($request->session_id,$b['NO_FP']);
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
                        // Move File FP Fisik dari Temp Ke Folder Asli Serta Return Credentials
                        $getPath = $this->moveFileTTfFromTemp($b['NO_FP'],$a['CABANG'],$getTtfNumber,$b['FP_TYPE']);
                        if($ttf_type == 1){
                            $saveToFpFisik = $this->insertToSysFpFisik($b['NO_FP'],$getPath['FILE_NAME'],$getPath['REAL_NAME'],$getPath['CONCAT_PATH'],$getTtfNumber);
                            $sys_fp_fisik_temp = new SysFpFisikTemp();
                            $deleteTempFisik = $sys_fp_fisik_temp->deleteSysFpFisikBySessionAndFpNum($request->session_id,$b['NO_FP']);
                        }
                        // Delete SysFPFisikTemp
                    }
                    if($request->hasFile('file_lampiran'))
                    {
                        $this->saveLampiran($request->file_lampiran,$getPath['DIR_NO_TTF'],$idHeader);
                    }
                }
                $concat_ttf_num = rtrim($concat_ttf_num, ',');
                $updateHeaders = $ttf_headers->updateTtfInsert($concat_ttf_num);
                
            },5);
            $this->deleteTmpAfterSave($request->session_id);
            return response()->json([
                    'status' => 'success',
                    'message' => 'TTF Berhasil Disimpan!',
                ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveTTfUpload($session_id,$user_id){
        $ttf_tmp_table = new TtfTmpTable();
        $ttf_headers = new TtfHeader();
        $ttf_fp = new TtfFp();
        // $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($request->supp_site_code,$request->branch_code,$request->session_id);
        // $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($request->supp_site_code,$request->branch_code,$request->session_id);
        $dataHeader = $ttf_tmp_table->getDataTTfTmpForInsertTTf($session_id);
        $dataFpTmp = $ttf_tmp_table->getDataTTFTmpFP($session_id);
        $user_id = $user_id;
        // print_r($data);
        $concat_ttf_num = '';
        try{
            DB::transaction(function () use($dataHeader,$user_id,$dataFpTmp,$ttf_tmp_table,$concat_ttf_num,$ttf_headers,$session_id){
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
                        'FLAG_GO' => $a['FLAG_GO'],
                        'FLAG_PPN' => $a['FLAG_PPN'],
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
                        $getDataBPBperFP = $ttf_tmp_table->getDataTTFTmpBPB($a['SUPP_SITE'],$a['CABANG'],$b['NO_FP']);
                        // $getDataBPBperFP = $ttf_tmp_table->getDataTTFTmpBPB($session_id,$b['NO_FP']);
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
                        // Move File FP Fisik dari Temp Ke Folder Asli Serta Return Credentials
                        $getPath = $this->moveFileTTfFromTemp($b['NO_FP'],$a['CABANG'],$getTtfNumber,$b['FP_TYPE']);
                        if($ttf_type == 1){
                            $saveToFpFisik = $this->insertToSysFpFisik($b['NO_FP'],$getPath['FILE_NAME'],$getPath['REAL_NAME'],$getPath['CONCAT_PATH'],$getTtfNumber);
                            $sys_fp_fisik_temp = new SysFpFisikTemp();
                            $deleteTempFisik = $sys_fp_fisik_temp->deleteSysFpFisikBySessionAndFpNum($session_id,$b['NO_FP']);
                        }
                        // Delete SysFPFisikTemp
                    }
                    // if($request->hasFile('file_lampiran'))
                    // {
                    //     $this->saveLampiran($request->file_lampiran,$getPath['DIR_NO_TTF'],$idHeader);
                    // }
                }
                $concat_ttf_num = rtrim($concat_ttf_num, ',');
                $updateHeaders = $ttf_headers->updateTtfInsert($concat_ttf_num);
                
            },5);
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

    public function moveFileTTfFromTemp($no_fp,$cabang,$no_ttf,$tipe_faktur){
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
            chmod($dir_bulan, 0777);
        }
        $dir_cabang = public_path('/file_djp_ttf_idm/'.$year.'/'.$month.'/'.$cabang);
        if(!file_exists( $dir_cabang ) && !is_dir( $dir_cabang )){
            mkdir($dir_cabang);
            chmod($dir_cabang, 0777);
        }
        $dir_no_ttf = public_path('/file_djp_ttf_idm/'.$year.'/'.$month.'/'.$cabang.'/'.$no_ttf);
        if(!file_exists( $dir_no_ttf ) && !is_dir( $dir_no_ttf )){
            mkdir($dir_no_ttf);
            chmod($dir_no_ttf, 0777);
        }
        if($tipe_faktur == 1){
            $sys_fp_fisik_temp = new SysFpFisikTemp();
            $getDataFpFisik = $sys_fp_fisik_temp->getDataSysFpFisikTmpByNoFp($no_fp);
            $concatPath = $dir_no_ttf.'/'.$getDataFpFisik->FILENAME;
            File::move($getDataFpFisik->PATH_FILE, $concatPath);
            $return_path['CONCAT_PATH'] = $concatPath;
            $return_path['FILE_NAME'] = $getDataFpFisik->FILENAME;
            $return_path['REAL_NAME'] = $getDataFpFisik->REAL_NAME;
        }
        $return_path['DIR_NO_TTF'] = $dir_no_ttf;
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
    public function insertToSysFpFisik($fp_num,$nama_file,$real_name,$path,$ttf_number){
        $sys_fp_fisik = new SysFpFisik();

        $insert = SysFpFisik::create([
            "FP_NUM" => $fp_num,
            "FILENAME" => $nama_file,
            "REAL_NAME" => $real_name,
            "PATH_FILE" => $path,
            "TTF_NUMBER" => $ttf_number,
            "CREATED_DATE" => date('Y-m-d')
        ]);

        if($insert){
            return 1;
        }else{
            return 0;
        }
    }

    public function uploadTTF(Request $request){
        $ttf_upload_tmp = new TtfUploadTmp();
        $deleteUploadTmp = $ttf_upload_tmp->deleteTtfUploadTmpBySessId($request->session_id);   
        if($request->hasFile('file_csv')){
            $fileName = $request->file_csv->hashName();
            $real_name = $request->file_csv->getClientOriginalName();
            $size = $request->file_csv->getSize();
            // print_r($fileName);
            $row = 1;
            $flag_error=false;
            $error_arr = array();
            $message = '';
            $errorValidasiDjp = '';
            $counter_error_djp = 0;
            if($request->file_csv->move(public_path('/file_upload_csv'), $fileName)){
                $file_handle = fopen(public_path('/file_upload_csv/'.$fileName), 'r');
                $data_csv =fgetcsv($file_handle, 0, $request->delimiter);
			    if(!isset($data_csv[1])){
                    return response()->json([
                            'status' => 'error',
                            'message' => 'Isi file kosong atau separator tidak sesuai.',
                    ]);
			    	$flag_error=true;
			    }else{
				    if((strtoupper($data_csv[0]) !='NO BPB' || strtoupper($data_csv[1])!='JENIS FAKTUR PAJAK' || strtoupper($data_csv[2])!='NO FAKTUR PAJAK' || strtoupper($data_csv[3])!='TGL FAKTUR PAJAK' || strtoupper($data_csv[4])!='NILAI DPP' || strtoupper($data_csv[5])!='NILAI PPN') && $row==1){
                        return response()->json([
                                'status' => 'error',
                                'message' => 'Template baris pertama CSV tidak sesuai format..',
                        ]);
				    	$flag_error=true;	
				    }else{
                        $message = DB::transaction(function () use ($flag_error,$data_csv,$request,$file_handle){
                            try{
                                $line = 1;
                                if($flag_error == false){
                                    while (!feof($file_handle)) {
                                        $data_csv = fgetcsv($file_handle, 1000, $request->delimiter);
                                        if($data_csv != false){
                                            $fp_type = 0;
                                            if ($data_csv[1] == 'STD')
                                            {
                                                $fp_type = 1;
                                            }
                                            else if ($data_csv[1] == 'NFP')
                                            {
                                                $fp_type = 2;
                                            }
                                            // else if ($data_csv[1] == 'KHS')
                                            // {
                                            //     $fp_type = 3;
                                            // }
                                            if($fp_type == 0){
                                                    $insertToUploadTmp = TtfUploadTmp::create([
                                                        "SESS_ID" => $request->session_id,
                                                        "LINE" => $line,
                                                        "BPB_NUM" => $data_csv[0],
                                                        "FP_TYPE" => $fp_type,
                                                        "NO_FP" => $data_csv[2],
                                                        "FP_DATE" => $data_csv[3],
                                                        "FP_DPP" => $data_csv[4],
                                                        "FP_TAX" => $data_csv[5],
                                                        "STATUS" => "ERROR"
                                                    ]);
                                                
                                            }else if($fp_type == 2){
                                                $insertToUploadTmp = TtfUploadTmp::create([
                                                    "SESS_ID" => $request->session_id,
                                                    "LINE" => $line,
                                                    "BPB_NUM" => $data_csv[0],
                                                    "FP_TYPE" => $fp_type,
                                                    "NO_FP" => $data_csv[2],
                                                    "FP_DATE" => $data_csv[3],
                                                    "FP_DPP" => 0,
                                                    "FP_TAX" => 0,
                                                    "STATUS" => "VALID"
                                                ]);
                                            }else{
                                                    $insertToUploadTmp = TtfUploadTmp::create([
                                                        "SESS_ID" => $request->session_id,
                                                        "LINE" => $line,
                                                        "BPB_NUM" => $data_csv[0],
                                                        "FP_TYPE" => $fp_type,
                                                        "NO_FP" => $data_csv[2],
                                                        "FP_DATE" => $data_csv[3],
                                                        "FP_DPP" => $data_csv[4],
                                                        "FP_TAX" => $data_csv[5],
                                                        "STATUS" => "VALID"
                                                    ]);
                                            }
                                            }
                                            $line++;
                                    }
                                }
                                $message = $this->validateUploadTemp($request->jumlah_fp_yang_diupload,$request->session_id,$request->user_id);
                                return $message;
                            }catch (\Exception $e) {
                                return $e->getMessage();
                            }
                        },5);

                    }
                }
            }
        }
        if($message['status']=='OK'){
            // $this->approveUpload($request->session_id,$request->user_id);
            // $this->approveUpload($request->session_id,$request->user_id);
            // $this->deleteTmpAfterApproveCsv($request->session_id);
            return response()->json([
                    'status' => 'success',
                    'message' => $message['message'],
                ]);
        }else{
            $ttf_upload_tmp = new TtfUploadTmp();
            $deleteUploadTmp = $ttf_upload_tmp->deleteTtfUploadTmpBySessId($request->session_id);
            return response()->json([
                    'status' => 'error',
                    'message' => $message['message'],
                ]);
        }
    }

    public function verifikasiDJP(Request $request){
        if($request->hasFile('file_djp')){
            foreach($request->file_djp as $key => $file)
            {
                // $fileName = time().'.'.$file->extension();
                $fileName = $file->hashName();
                $real_name = $file->getClientOriginalName();
                $size = $file->getSize();
                // print_r($fileName);
                // echo "";
                $data = array();
                if($file->move(public_path('/file_temp_fp'), $fileName)){
                    $pdfParser = new \Smalot\PdfParser\Parser();
                    $pdf = $pdfParser->parseFile(public_path('/file_temp_fp/'.$fileName));
                    $content = $pdf->getText();
                    print_r($content);
                }
                // $data[$i]=$fileName;

            }
        }
            // $convert_image_controller = new ConvertImageController();
            // $temp_upload_djp_csv = new TempUploadDjpCsv();
            // $getDataTempUploadCsv = $temp_upload_djp_csv->getDataTempUploadDjpCsvBySessIdForUpload($request->session_id);
            // // fileUploadPostUploadCsv
            // $ttf_upload_tmp = new TtfUploadTmp();
            // $prepopulated_fp = new PrepopulatedFp();
            // foreach($getDataTempUploadCsv as $a){
            //     $fileNameConverted = $convert_image_controller->convertFpPdfToImageUploadCsv($a->FILE_NAME);
            //     $cek_qr = $convert_image_controller->readQr($fileNameConverted);
            //     $explodeLink = explode("/",$cek_qr);
            //     $npwp_penjual = substr($explodeLink[5], 0, 2) .
            //         "." .
            //         substr($explodeLink[5], 2, 3) .
            //         "." .
            //         substr($explodeLink[5], 5, 3) .
            //         "." .
            //         substr($explodeLink[5], 8, 1) .
            //         "-" .
            //         substr($explodeLink[5], 9, 3) .
            //         "." .
            //         substr($explodeLink[5], 12, 3);
            //     $no_faktur =
            //         substr($explodeLink[6], 0, 3) .
            //         "-" .
            //         substr($explodeLink[6], 3, 2) .
            //         "." .
            //         substr($explodeLink[6], 5, 8);
            //     $getDataTempBySessionId= $ttf_upload_tmp->getNoFpTmpBySessionIdAndNoFp($request->session_id,$no_faktur);
            //     if($getDataTempBySessionId){
                    
            //         $validateUploadDjp = $prepopulated_fp->getPrepopulatedFpByNoFpAndNpwp($npwp_penjual,$no_faktur);
            //         if($validateUploadDjp==0){
            //             $counter_error_djp ++;
            //             $errorValidasiDjp .= "<br> NO_FP ' . $getDataTempBySessionId->NO_FP . ' Tidak terdaftar pada Prepopulated FP";
            //         }else{
            //             $getNomorFp = $prepopulated_fp->getFpByNoFpAndNpwp($npwp_penjual,$no_faktur);
            //             $updateTempDjpFisikCsv =  TempUploadDjpCsv::where('ID',$a->ID)->update([
            //                 "NO_FP" => $getNomorFp->NOMOR_FAKTUR
            //             ]);
            //         }
            //     }else{
            //         $errorValidasiDjp .= " File DJP ' . $a->REAL_NAME . ' tidak terdaftar pada CSV";
            //         $counter_error_djp ++;
            //     }
            // }
            // if($counter_error_djp > 0){
            //     return response()->json([
            //             'status' => 'error',
            //             'message' => $errorValidasiDjp,
            //         ]);
            // }else{
            //     $message = $this->validateUploadTemp($request->jumlah_fp_yang_diupload,$request->session_id,$request->user_id);
            // }
    }
    public function cekUploadLampiran(Request $request){
        $data = array();
        if($request->hasfile('file_lampiran')){
            foreach($request->file_lampiran as $key => $file)
            {
                // $fileName = time().'.'.$file->extension();
                $fileName = $file->hashName();
                $real_name = $file->getClientOriginalName();
                $size = $file->getSize();
                // print_r($fileName);
                // echo "";
                $data = array();
                if($file->move(public_path('/file_temp_fp'), $fileName)){
                }
                // $data[$i]=$fileName;
                array_push($data,$fileName);

            }
        }
    }

    public function validateUploadTemp($jumlah_fp_yg_diupload,$session_id,$user_id){
        $fp_date = '';
        $bpb_date = '';
        $error = '';
        $fp_dicsv = [];
        $jumlah_fp_dicsv = 0;
        $ttf_upload_tmp = new TtfUploadTmp();
        $ttf_data_bpb = new TtfDataBpb();
        $data = array();
        $getDataTempBySessionId= $ttf_upload_tmp->getTtfTmpBySessionId($session_id);
        // Update Tmp Untuk Melengkapi data BPB
        foreach($getDataTempBySessionId as $a){
            $getDataBpbByBpbNum = $ttf_data_bpb->getDataBpbByNoBPB($a->BPB_NUM);
            if($getDataBpbByBpbNum){
                $insertToUploadTmp = TtfUploadTmp::where('ID',$a->ID)->update([
                    "SUPP_SITE" => $getDataBpbByBpbNum->VENDOR_SITE_CODE,
                    "BPB_DATE" => $getDataBpbByBpbNum->BPB_DATE,
                    "CABANG" => $getDataBpbByBpbNum->BRANCH_CODE,
                    "BPB_PPN" => $getDataBpbByBpbNum->BPB_TAX,
                    "BPB_AMOUNT" => $getDataBpbByBpbNum->BPB_DPP,
                    "FLAG_GO" => $getDataBpbByBpbNum->FLAG_GO,
                    "FLAG_PPN" => $getDataBpbByBpbNum->FLAG_PPN
                ]);
            }
        }
        $getDataTempBySessionId= $ttf_upload_tmp->getTtfTmpBySessionId($session_id);
        foreach($getDataTempBySessionId as $a){
            if ($a->FP_TYPE == 0)
            {
                $error .= 'Error Line ' . $a->LINE . ' : Tipe Faktur Pajak tidak diketahui<br>';
            }
            if ($a->FP_TYPE == 3)
            {
                $error .= 'Error Line ' . $a->LINE . ' : Tipe Faktur Pajak Khusus harap di input manual<br>';
            }

            if ($a->FP_TYPE == 2)
            {
                if ($a->NO_FP != '-' || $a->FP_DPP != '0' || $a->FP_TAX != '0')
                {
                    $error .= 'Error Line ' . $a->LINE . ' : No Faktur Pajak dengan Tipe NFP harus berisi strip (-)<br>';
                }
            }

            if ($a->FP_TYPE == 1)
            {
                $regex = '^[0-9]{3}.[0-9]{3}-[0-9]{2}.[0-9]{8}$^';
                if (preg_match($regex, $a->NO_FP))
                {
                    array_push($fp_dicsv, $a->NO_FP);
                }
                else
                {
                    $error .= ' Error  Line ' . $a->LINE . ' : Format No Faktur ' . $a->NO_FP . ' tidak sesuai<br>';
                }
            }
            if ($a->FORMAT_DATE == '')
            {
                $error .= 'Error Line ' . $a->LINE . ': Tanggal tidak valid, format yang seharusnya adalah dd/mm/yyyy<br>';
            }

            if (!is_numeric($a->FP_DPP) || !is_numeric($a->FP_TAX))
            {
                $error .= 'Error Line ' . $a->LINE . ': Nilai DPP atau Nilai PPN bukan angka<br>';
            }

            if ($a->BPB_DATE == '')
            {
                $error .= 'Error Line ' . $a->LINE . ': No BPB ' . $a->BPB_NUM . ' tidak ditemukan<br>';
            }

            $fp_date = date_create($a->FORMAT_DATE);
            $bpb_date = date_create($a->BPB_DATE);
            if ($a->FP_DATE < $a->BPB_DATE){
                if (date_diff($fp_date, $bpb_date)->days > 89){
                    $error .= 'Error Line ' . $a->LINE . ': Tanggal faktur sudah expired<br>';
                }
            }
            // Validasi Apakah BPB Sudah Digunakan Atau Belum (harus ada di ttf_data_bpb dan used flag nya N)
            if ($error == ''){
                $ttf_data_bpb = new TtfDataBpb();

                $getCountDataBpb= $ttf_data_bpb->validateCountBPBByBPBNumber($a->BPB_NUM);
                if($getCountDataBpb > 0){
                    $error .= 'Error Line ' . $a->LINE . ': No BPB ' . $a->BPB_NUM . ' telah digunakan<br>';
                }
            }
            // no bpb harus milik user
            if ($error == '')
            {
                $sys_mapp_supplier = new SysMapSupplier();
                $getCountMapSuppForBpb =  $sys_mapp_supplier->validateUploadBpbByUserId($user_id,$a->SUPP_SITE,$a->CABANG);
                if ($getCountMapSuppForBpb == 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': No BPB ' . $a->BPB_NUM . ' tidak dapat digunakan oleh akun ini<br>';
                }
            }
            // faktur tidak boleh ada di ttf_fp
            if ($error == '' && $a->FP_TYPE == 1)
            {
                $ttf_fp = new TtfFp();
                $getCountTtfFp = $ttf_fp->validateFPisUsedByFpNum($a->NO_FP);
                if ($getCountTtfFp > 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': No Faktur ' . $a->NO_FP . ' telah digunakan<br>';
                }

            }
            //no fp harus terdaftar di table prepopulated fp
            if ($error == '' && $a->FP_TYPE == 1)
            {
                $prepopulated_fp = new PrepopulatedFp();

                $getCountPrepopulatedFp = $prepopulated_fp->checkPrepopulatedFPByNoFakturAndUsedFlag($a->NO_FP);
                if ($getCountPrepopulatedFp == 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': No Faktur ' . $a->NO_FP . ' belum terdaftar.<br>';
                }

            }
            // no bpb tidak boleh ganda
            if ($error == '')
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkDoubleBPB = $ttf_upload_tmp->checkDoubleBpbForUpload($session_id,$a->BPB_NUM,$a->ID);
                if ($checkDoubleBPB > 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': No BPB ' . $a->BPB_NUM . ' Ganda dalam file ini <br>';
                }
            }
            // cek supplier exist
            if ($error == '')
            {
                $sys_supp_site = new SysSuppSite();

                $getCountSupplier = $sys_supp_site->valdiateSupplierExists($a->SUPP_SITE,$a->CABANG);
                if ($getCountSupplier == 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': Kombinasi kode supplier dan cabang tidak ditemukan<br>';
                }
            }
            $nilai_ttf = $a->FP_DPP;
        }
        $jumlah_fp_dicsv = count(array_unique($fp_dicsv));
        if ($jumlah_fp_yg_diupload != $jumlah_fp_dicsv)
        {
            $error .= ' Error : Jumlah Faktur yang diupload di csv tidak sama dengan Jumlah File DJP yang diupload <br>';

        }
        foreach($getDataTempBySessionId as $a){
            // Validate Flag Go
            if($error == ''){
                $ttf_upload_tmp = new TtfUploadTmp();
                $count = $ttf_upload_tmp->validateFlagGoPerFp($session_id,$a->NO_FP);
                if($count[0]->COUNT_DATA > 1){
                     $error .= ' Error Flag Go : Faktur ' . $a->NO_FP . ' harus memiliki BPB Flag GO yang Seragam <br>';
                }
            }
            // Validate Flag PPn
            if($error == ''){
                $ttf_upload_tmp = new TtfUploadTmp();
                $count = $ttf_upload_tmp->validateFlagPpnPerFp($session_id,$a->NO_FP);
                if($count[0]->COUNT_DATA > 1){
                     $error .= ' Error Flag Ppn : Faktur ' . $a->NO_FP . ' harus memiliki BPB Flag PPN yang Seragam <br>';
                }
            }
            // satu faktur hanya boleh satu dpp
            if ($error == '')
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkDoubleDpp = $ttf_upload_tmp->validateDoubleDPP($session_id,$a->FP_DPP,$a->NO_FP);
                if ($checkDoubleDpp > 0)
                {
                    $error .= ' Error Line ' . $a->LINE . ': Nilai DPP Satu Faktur harus sama <br>';
                }
            }
            // satu faktur hanya boleh satu ppn
            if ($error == '')
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkDoublePpn = $ttf_upload_tmp->validateDoublePPN($session_id,$a->FP_TAX,$a->NO_FP);
                if ($checkDoublePpn > 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ':Nilai PPN Satu Faktur harus sama <br>';
                }
            }
            // satu faktur hanya boleh satu tanggal
            if ($error == '')
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkDoubleDate = $ttf_upload_tmp->validateDoubleDate($session_id,$a->FP_DATE,$a->NO_FP);
                if ($checkDoubleDate > 0)
                {
                    $error .= 'Error Line ' . $a->LINE . ': Satu Faktur harus memiliki tanggal yang sama <br>';
                }
            }

            // satu bpb hanya dalam satu faktur
            if ($error == '')
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkDoubleBpb = $ttf_upload_tmp->validateDoubleBPB($session_id,$a->NO_FP,$a->BPB_NUM);
                if ($checkDoubleBpb > 0)
                {
                    $error .= ' Error Line ' . $a->LINE . ': Satu BPB hanya boleh untuk satu faktur <br>';
                }
            }

            // satu faktur hanya boleh satu cabang
            if ($error == '' && $a->FP_TYPE == 1)
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkBranchInOneFp = $ttf_upload_tmp->validateBranchInOneFp($session_id,$a->NO_FP,$a->CABANG);
                if ($checkBranchInOneFp > 0)
                {
                    $error .= ' Error Line ' . $a->LINE . ' : Satu faktur hanya boleh satu cabang <br>';
                }
            }

            // satu faktur hanya boleh satu supplier
            if ($error == '' && $a->FP_TYPE == 1)
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $checkSuppInFP = $ttf_upload_tmp->checkSuppInFp($session_id,$a->NO_FP,$a->SUPP_SITE);
                if ($checkSuppInFP > 0)
                {
                    $error .= ' Error Line ' . $a->LINE . ': Satu faktur hanya boleh satu supplier <br>';
                }
            }
            $ttf_param_table = new TtfParamTable();
            $selisih = $ttf_param_table->getMaxSelisih()->MAX_SELISIH;

            if ($error == '' && $a->FP_TYPE == 1)
            {
                $ttf_upload_tmp = new TtfUploadTmp();
                $getSelisih = $ttf_upload_tmp->checkSelisihFP($session_id);
                
                foreach ($getSelisih as $row)
                {
                    if ($error == '')
                    {
                        if (($row->SELISIH_DPP + $row->SELISIH_PPN) > $selisih)
                        {
                            $error .= ' Error Selisih : Faktur ' . $row->NO_FP . ' selisih dengan nilai dari DJP ' . number_format(($row->SELISIH_DPP + $row->SELISIH_PPN) , 0, '.', ',').'<br>';
                        }
                    }

                    if ($error == '')
                    {
                        if ($row->NO_FP == '-' && $row->FP_TAX != '0')
                        {
                            $error .= 'Error Faktur : Nilai PPN Tanpa Faktur Pajak Harus 0. Periksa kembali BPB yang dipilih!<br>';
                        }
                    }

                    if ($row->NO_FP != '-' && ($row->FP_DPP == '0' || $row->FP_TAX == '0'))
                    {
                        $error .= 'Error Faktur : Nilai DPP atau PPN Faktur Pajak ' . $row->NO_FP . ' Tidak boleh 0. <br>';
                    }

                }
            }
        }

        //SEQ NUMBER
        $status = 'VALID';
        $ttf_upload_tmp = new TtfUploadTmp();
        $getDataSiteAndBranchFromUpload = $ttf_upload_tmp->getSiteCodeAndBranch($session_id,$status);
        $no = 1;

        foreach ($getDataSiteAndBranchFromUpload as $b)
        {
            $updateTtfUploadTmp = TtfUploadTmp::where('SESS_ID',$session_id)->where('SUPP_SITE',$b->SUPP_SITE)->where('CABANG',$b->CABANG)->update([
                "SEQ_NUM" => $no
            ]);
            $no++;
        }
        $no--;
        // UPDATE NILAI FP UNTUK TANPA FP
        if ($error == '')
        {
            $ttf_upload_tmp = new TtfUploadTmp();
            $getSeqNum = $ttf_upload_tmp->getSeqNumBySessionId($session_id);
            
            foreach ($getSeqNum as $tu)
            {
                $getSumBPbAndPPN = $ttf_upload_tmp->getSumAmountForNoFp($session_id,$tu->SEQ_NUM);

                $update = TtfUploadTmp::where('SESS_ID',$session_id)->where('FP_TYPE',2)->where('SEQ_NUM',$tu->SEQ_NUM)->update([
                    "FP_DPP" => $getSumBPbAndPPN->SUM_BPB_AMOUNT,
                    "FP_TAX" => $getSumBPbAndPPN->SUM_BPB_PPN
                ]);
            }
        }

        //GET MAX SELISIH
        $ttf_param_table = new TtfParamTable();
        $selisih = $ttf_param_table->getMaxSelisih()->MAX_SELISIH;

        //VALIDASI FAKTUR SELISIH dan DPP FP tidak boleh 0 dan PPN harus
        $nilai_ttf = 0;
        if ($error == '')
        {
            $ttf_upload_tmp = new TtfUploadTmp();
            $getCheckUploadTmp = $ttf_upload_tmp->checkDataUploadTmp($session_id);
            
            foreach ($getCheckUploadTmp as $row)
            {
                if ($error == '')
                {
                    if (($row->SELISIH_DPP + $row->SELISIH_PPN) > $selisih)
                    {
                        $error .= ' Error Selisih : Faktur ' . $row->NO_FP . ' selisih ' . number_format(($row->SELISIH_DPP + $row->SELISIH_PPN) , 0, '.', ',').'<br>';
                    }
                }

                if ($error == '')
                {
                    if ($row->NO_FP == '-' && $row->FP_TAX != '0')
                    {
                        $error .= 'Error Faktur : Nilai PPN Tanpa Faktur Pajak Harus 0. Periksa kembali BPB yang dipilih!<br>';
                    }
                }

                // if($error == ''){
                // 	if($row->NO_FP != '-' && ($row->FP_DPP == '0' || $row->FP_TAX == '0')){
                // 		$error = ' \n Error Faktur : Nilai DPP atau PPN Faktur Pajak '.$row->NO_FP.' Tidak boleh 0.';
                // 	}
                // }
                if ($row->NO_FP != '-' && ($row->FP_DPP == '0' || $row->FP_TAX == '0'))
                {
                    $error .= 'Error Faktur : Nilai DPP atau PPN Faktur Pajak ' . $row->NO_FP . ' Tidak boleh 0.<br>';
                }

                $nilai_ttf += $row->NILAI_FP;
            }
        }
        // print_r($error);
        if ($error == '')
        {
            $data['status'] = 'OK';
            $data['message'] = 'TTF berhasil di upload.<br><br> <br>Jumlah TTF = ' . $no . '. <br>Nilai keseluruhan TTF = ' . number_format($nilai_ttf, 0, '.', ',') . '. <br>';
        }
        else
        {
            $status = 'ERROR';
            $updateUploadTmp = TtfUploadTmp::where('SESS_ID',$session_id)->update([
                "STATUS" => $status
            ]);
            $data['status'] = 'ERROR';
            $data['message'] = $error;
        }
        // print_r($error);
        return $data;
    }

    public function approveUpload($session_id,$user_id){
        $ttf_tmp_table = new TtfTmpTable();
        $insertToTtfTmpTable = $ttf_tmp_table->insertFromUploadCsv($session_id);
        $sys_fp_fisik_temp = new SysFpFisikTemp();
        $moveDjpFileTmpToSysFpTemp = $sys_fp_fisik_temp->insertFromTempDjpCsv($session_id);
        $this->saveTTfUpload($session_id,$user_id);
    }
    public function testAPIUploadCSV(){
        $fileName = $request->file_csv->hashName();
        $real_name = $request->file_csv->getClientOriginalName();
        $size = $request->file_csv->getSize();
        if($request->file_csv->move(public_path('/file_upload_csv'), $fileName)){
            print_r("Nama File Yang diUpload =".$real_name);
            echo "<br>";
            if($request->hasfile('file_djp')){
                foreach($request->file_lampiran as $key => $file)
                {
                    // $fileName = time().'.'.$file->extension();
                    $fileName = $file->hashName();
                    $real_name = $file->getClientOriginalName();
                    $size = $file->getSize();
                    // print_r($fileName);
                    // echo "<br>";
                    $data = array();
                    if($file->move(public_path('/file_temp_fp'), $fileName)){

                    }
                    // $data[$i]=$fileName;
                    array_push($data,$fileName);

                }
            }
        }
    }

    public function downloadTemplateCsv(){
        $file= public_path('/template_csv_ttf.csv');
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_csv_ttf.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Response::download($file,'template_csv_ttf.csv' ,$headers);
    }

    public function deleteTmpAfterApproveCsv($session_id){
        $ttf_upload_tmp = new TtfUploadTmp();
        $deleteUploadTmp = $ttf_upload_tmp->deleteTtfUploadTmpBySessId($session_id);
        $temp_upload_djp_csv = new TempUploadDjpCsv();
        $deleteUploadDjpCsv = $temp_upload_djp_csv->deleteTempUploadDjpCsvBySessId($session_id);
        $sys_fp_fisik_temp = new SysFpFisikTemp();
        $deleteFpFisikTemp = $sys_fp_fisik_temp->deleteSysFpFisikTempBySessId($session_id);
        $ttf_tmp_table = new TtfTmpTable();
        $deleteTtfTmpTable = $ttf_tmp_table->deleteTmpTableSessId($session_id);
    }

    public function deleteTmpAfterSave($session_id){
        $ttf_upload_tmp = new TtfUploadTmp();
        $deleteUploadTmp = $ttf_upload_tmp->deleteTtfUploadTmpBySessId($session_id);
        $sys_fp_fisik_temp = new SysFpFisikTemp();
        $deleteFpFisikTemp = $sys_fp_fisik_temp->deleteSysFpFisikTempBySessId($session_id);
        $ttf_tmp_table = new TtfTmpTable();
        $deleteTtfTmpTable = $ttf_tmp_table->deleteTmpTableSessId($session_id);
    }
    
}
