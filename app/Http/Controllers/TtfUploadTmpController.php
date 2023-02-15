<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfUploadTmp;

class TtfUploadTmpController extends Controller
{
    //
    public function getDataForInquiryUpload(Request $request){
       $ttf_upload_tmp = new TtfUploadTmp();
       $data = $ttf_upload_tmp->getDataForInquiryUpload($request->session_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }
}
