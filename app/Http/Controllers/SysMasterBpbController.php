<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysMasterBpb;

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
        $nomor = $offset+1;
        foreach ($data as $a){
            // print_r($a->FP_TYPE);
            // $dataLines = $ttf_tmp_table->getDataDetailBPBperFP($request->supp_site_code,$request->branch_code,$a->NO_FP,$request->session_id);
            $dataArray[$i]['NOMOR'] = $nomor;
            $dataArray[$i]['ID'] = $a->ID;
            $dataArray[$i]['NO_BPB'] = $a->FP_TYPE;
            $dataArray[$i]['TGL_BPB'] = $a->NO_FP;
            $dataArray[$i]['DPP'] = $a->TANGGAL_FP;
            $dataArray[$i]['TAX'] = $a->FP_DPP;
            $dataArray[$i]['VENDOR_SITE_CODE'] = $a->FP_TAX;
            $dataArray[$i]['INVOICE_NUM'] = $a->JUMLAH_BPB;
            $dataArray[$i]['KODE_DC'] = $a->KODE_DC;
            $dataArray[$i]['JUMLAH_PPN_BPB'] = $a->JUMLAH_PPN_BPB;
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


}
