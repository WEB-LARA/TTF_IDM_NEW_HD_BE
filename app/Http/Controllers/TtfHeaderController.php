<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfHeader;
use App\Models\SysFpFisik;
use App\Models\TtfFp;
use App\Models\TtfLampiran;
use App\Models\TtfLines;
use ZipArchive;
use Response;
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

    public function downloadLampiran(Request $request){
        $sys_fp_fisik = new SysFpFisik();
        $getDataFpFisik = $sys_fp_fisik->getDataByTtfNumber('230022473841');
        $ttf_lampiran = new TtfLampiran();
        $getDataTtfLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID(176);
        // $zip = new ZipArchive();
        $zip = new \ZipArchive();
        if ($zip->open(public_path('trigger_zip/test_new.zip'), \ZipArchive::CREATE) === TRUE)
        {
            foreach($getDataFpFisik as $a){
                $zip->addFile($a->PATH_FILE,$a->REAL_NAME);
            }
            foreach($getDataTtfLampiran as $b){
                $zip->addFile($b->PATH_FILE,$b->REAL_NAME);
            }
        }

        
        $zip->close();
        $file= public_path('trigger_zip/test_new.zip');
        $headers = array(
            "Content-type"        => "application/zip",
            "Content-Disposition" => "attachment; filename=test_zip.zip",
            "Content-Transfer-Encoding" => "Binary",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Response::download($file,'test_zip.zip' ,$headers);
        // header('Content-disposition: attachment; filename=download.zip');
        // header('Content-type: application/zip');
        // readfile(public_path('trigger_zip/test_new.zip'));
    }
    
    public function submitTtf(Request $request){
        $validate = TtfHeader::whereIn('TTF_ID',$request->ttf_id)->update([
            'STATUS'=>'S'
        ]);
        if($validate){
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Berhasil di Validasi!'
                ]);
        }else{
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Gagal di Validasi!'
                ]);
        }
    }

    public function cancelTtf(Request $request){
        $validate = TtfHeader::whereIn('TTF_ID',$request->ttf_id)->update([
            'STATUS'=>'C'
        ]);
        if($validate){
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Berhasil di Cancel!'
                ]);
        }else{
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Gagal di Cancel!'
                ]);
        }
    }

    public function getDetailTtfByTtfId(Request $request){
        $ttf_header = new TtfHeader();
        $ttf_fp = new TtfFp();
        $ttf_lines = new TtfLines();
        $dataArray = array();
        $dataArrayFp = array();
        $dataArrayBpb = array();
        $i = 0;
        $j = 0;
        $k = 0;
        $dataHeader = $ttf_header->getDetailTtfByTtfId($request->ttf_id);
        foreach ($dataHeader as $a){
            // print_r($a->FP_TYPE);
            $dataFp = $ttf_fp->getFpByTtfId($request->ttf_id);
            $dataArray[$i]['TTF_NUM'] = $a->TTF_NUM;
            $dataArray[$i]['TTF_DATE'] = $a->TTF_DATE;
            $dataArray[$i]['VENDOR_SITE_CODE'] = $a->VENDOR_SITE_CODE;
            $dataArray[$i]['TIPE_TTF'] = $a->TIPE_TTF;
            $dataArray[$i]['TOTAL_TTF'] = $a->TOTAL_TTF;
            $dataArray[$i]['NAMA_SUPPLIER'] = $a->NAMA_SUPPLIER;
            $dataArray[$i]['SUPP_TYPE'] = $a->SUPP_TYPE;
            $dataArray[$i]['NOMOR_NPWP'] = $a->NOMOR_NPWP;
            $dataArray[$i]['ALAMAT_SUPPLIER'] = $a->ALAMAT_SUPPLIER;
            foreach($dataFp as $b){
                $dataArrayFp[$j]['FP_NUM'] = $b->FP_NUM;
                $dataArrayFp[$j]['FP_DATE'] = $b->FP_DATE;
                $dataArrayFp[$j]['FP_DPP_AMT'] = $b->FP_DPP_AMT;
                $dataArrayFp[$j]['FP_TAX_AMT'] = $b->FP_TAX_AMT;
                $dataArrayFp[$j]['TIPE_FAKTUR'] = $b->TIPE_FAKTUR;
                $dataBpb = $ttf_lines->getDataBpbByTtfId($request->ttf_id);
                foreach($dataBpb as $c){
                    $dataArrayBpb[$k]['BPB_NUMBER'] = $b->BPB_NUMBER;
                    $dataArrayBpb[$k]['BPB_DPP'] = $b->BPB_DPP;
                    $dataArrayBpb[$k]['BPB_TAX'] = $b->BPB_TAX;
                    $k++;
                }
                $dataArrayFp[$j]['DATA_BPB'] = $dataArrayBpb;
                $j++;
            }
            $dataArray[$i]['DATA_FP'] = $dataArrayFp;
            $i++;
        }
            return response()->json([
                    'status' => 'success',
                    'data' => $dataArray
                ]);
    }
}
