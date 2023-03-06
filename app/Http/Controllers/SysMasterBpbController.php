<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysMasterBpb;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class SysMasterBpbController extends Controller
{
    public function getDataInquiryDownloadBpb(Request $request){
        $branch_code = $request->branch_code;
        $vendor_site_code = $request->vendor_site_code;
        $tanggal_bpb_start = $request->bpb_date_start;
        $tanggal_bpb_end = $request->bpb_date_end;
        $nomor_bpb = $request->nomor_bpb;
        $offset=$request->offset;
        $limit=$request->limit;
        $skip = ($limit*$offset) - $limit;
        $return_data = array();
        $dataArray = array();
        $getData = SysMasterBpb::join('sys_ref_branch', 'sys_ref_branch.BRANCH_UNIT_CODE', '=', 'sys_master_bpb.KODE_DC')
        ->select('sys_master_bpb.ID','NO_BPB','TGL_BPB','DPP','TAX','VENDOR_SITE_CODE','INVOICE_NUM','KODE_DC');
        
        if($branch_code){
            $getData = $getData->where('sys_ref_branch.BRANCH_CODE',$branch_code);
        }
        if($vendor_site_code){
            $getData = $getData->where('sys_master_bpb.VENDOR_SITE_CODE',$vendor_site_code);
        }
        if($tanggal_bpb_start && $tanggal_bpb_end){
            $getData = $getData->whereBetween('sys_master_bpb.TGL_BPB',array($tanggal_bpb_start,$tanggal_bpb_end));
        }
        if($nomor_bpb){
            $getData = $getData->where('sys_master_bpb.NO_BPB',$nomor_bpb);
        }

        $count_data = $getData->count();

        $data = $getData->skip($skip)->take($limit)->get();
        $i = 0;
        $nomor = $skip+1;
        foreach ($data as $a){
            // print_r($a->FP_TYPE);
            // $dataLines = $ttf_tmp_table->getDataDetailBPBperFP($request->supp_site_code,$request->branch_code,$a->NO_FP,$request->session_id);
            $dataArray[$i]['NOMOR'] = $nomor;
            $dataArray[$i]['ID'] = $a->ID;
            $dataArray[$i]['NO_BPB'] = $a->NO_BPB;
            $dataArray[$i]['TGL_BPB'] = $a->TGL_BPB;
            $dataArray[$i]['DPP'] = $a->DPP;
            $dataArray[$i]['TAX'] = $a->TAX;
            $dataArray[$i]['VENDOR_SITE_CODE'] = $a->VENDOR_SITE_CODE;
            $dataArray[$i]['INVOICE_NUM'] = $a->INVOICE_NUM;
            $dataArray[$i]['KODE_DC'] = $a->KODE_DC;
            // $dataArray[$i]['DATA_LINES'] = $dataLines;
            $i++;
            $nomor++;
        }

        return response()->json([
                'status' => 'success',
                'count' => $count_data,
                'data'=>$dataArray
        ]);
    }

    public function downloadBpb(Request $request){
        $getData = SysMasterBpb::whereIn('ID',[17360277,17359218])
        ->select('PATH_DATA')->get();
        $pdf = PDFMerger::init();
        foreach($getData as $a){
            // $pdf->addPDF($a->PATH_DATA, 'all');
            print_r($a->PATH_DATA);
            echo "<br>";
        }
        $pdf->addPDF('/usr/src/app/public/download_bpb/ezlmllnqnjAugIPr2Ee6XBFSOpH06CnSCDNEuFTu.pdf', 'all');
        $pdf->addPDF('/usr/src/app/public/download_bpb/VBS4So7vcV6ZnwP8LuW2LCJOhlRAvsH6feHPqpNT.pdf', 'all');
        $fileName = time().'.pdf';
        $pdf->merge();
        $pdf->save(public_path('folder_merge_bpb/'.$fileName));
        
        return response()->download(public_path('folder_merge_bpb/'.$fileName));
    }

}
