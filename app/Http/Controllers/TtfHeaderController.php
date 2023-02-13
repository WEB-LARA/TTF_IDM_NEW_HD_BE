<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfHeader;
use ZipArchive;
class TtfHeaderController extends Controller
{
    public function getDataInquiryTTF(Request $request){
        $ttf_header = new TtfHeader();
        $data = $ttf_header->getDataInquiryTTF($request->user_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function getDataInquiryDetailTTF(Request $request){
        $ttf_header = new TtfHeader();
        $data = $ttf_header->getDataInquiryDetailTTF($request->ttf_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function downloadLampiran(){
        // $zip = new ZipArchive();

        $zip = new \ZipArchive();
        if ($zip->open(public_path('trigger_zip/test_new.zip'), \ZipArchive::CREATE) === TRUE)
        {
            $zip->addFile($zip->addFile("/usr/src/app/public/file_djp_ttf_idm/2023/Feb/002/230022473841/IVTBy80U2SpaliM1nJvSDdbTkuQTiJ6JD726LMsp.pdf","IVTBy80U2SpaliM1nJvSDdbTkuQTiJ6JD726LMsp.pdf"));
        }
        $zip->close();
        // $filename = "test.zip";

        // if ($zip->open(public_path($filename), ZipArchive::CREATE)===TRUE) {
        //     $zip->addFile("/usr/src/app/public/file_djp_ttf_idm/2023/Feb/002/230022473841/","IVTBy80U2SpaliM1nJvSDdbTkuQTiJ6JD726LMsp.pdf");
        //     $zip->addFile("/usr/src/app/public/file_djp_ttf_idm/2023/Feb/002/230022473841/","IVTBy80U2SpaliM1nJvSDdbTkuQTiJ6JD726LMsp.pdf");
        
        // }

        header('Content-disposition: attachment; filename=download.zip');
        header('Content-type: application/zip');
        readfile(public_path('trigger_zip/test_new.zip'));
    }
}
