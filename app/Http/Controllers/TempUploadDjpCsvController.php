<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempUploadDjpCsv;

class TempUploadDjpCsvController extends Controller
{
    public function insertFileDjp(Request $request){
        if($request->hasFile('file_djp')){
            $fileName = $file->hashName();
            $real_name = $file->getClientOriginalName();
            $size = $file->getSize();
            if($request->file->move(public_path('/file_temp_fp'), $fileName)){
                $create = TempUploadDjpCsv::create([
                    "PATH_FILE" => public_path('/file_temp_fp/'.$fileName),
                    "FILE_NAME" =>$fileName,
                    "REAL_NAME" =>$real_name,
                    "SESSION_ID" => $request->session_id
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'gagal membaca file',
                ]);
            }
        }
    }
}
