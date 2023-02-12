<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempUploadDjpCsv;

class TempUploadDjpCsvController extends Controller
{
    public function insertFileDjp(Request $request){
        if($request->hasFile('file_djp')){
            $file = $request->file_djp;
            $fileName = $file->hashName();
            $real_name = $file->getClientOriginalName();
            $size = $file->getSize();
            if($file->move(public_path('/file_temp_fp'), $fileName)){
                $create = TempUploadDjpCsv::create([
                    "PATH_FILE" => public_path('/file_temp_fp/'.$fileName),
                    "FILE_NAME" =>$fileName,
                    "REAL_NAME" =>$real_name,
                    "SESSION_ID" => $request->session_id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sukses Menyimpan File!',
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'gagal membaca file',
                ]);
            }
        }
    }

    public function getFileDjpBySessionId(Request $request){
        $temp_upload_djp_csv = new TempUploadDjpCsv();
        $getData = $temp_upload_djp_csv->getDataTempUploadDjpCsvBySessId($request->session_id);

        if($getData){
            return response()->json([
                'status' => 'success',
                'data' => $getData,
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Gagal Membaca File!'
            ]);
        }
    }
}
