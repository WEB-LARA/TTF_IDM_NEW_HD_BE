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
        $getData = SysMasterBpb::join('sys_ref_branch', 'sys_ref_branch.BRANCH_UNIT_CODE', '=', 'sys_master_bpb.KODE_DC')
        ->select('NO_BPB','TGL_BPB','DPP','TAX','VENDOR_SITE_CODE','INVOICE_NUM','KODE_DC');
        
        if($branch_code){
            $getData = $getData->where('sys_ref_branch.BRANCH_CODE',$branch_code);
        }
        if($vendor_site_code){
            $getData = $getData->where('sys_master_bpb.VENDOR_SITE_CODE',$vendor_site_code);
        }
        if($bpb_date_start && $bpb_date_end){
            $getData = $getData->whereBetween('sys_master_bpb.TGL_BPB',array($bpb_date_start,$bpb_date_end));
        }
        if($nomor_bpb){
            $getData = $getData->where('sys_master_bpb.NO_BPB',$nomor_bpb);
        }

        $count_data = $getData->count();

        $data = $getData->skip($skip)->take($limit)->get();

        return response()->json([
                'status' => 'success',
                'count' => $count_data,
                'data'=>$data
        ]);
    }


}
