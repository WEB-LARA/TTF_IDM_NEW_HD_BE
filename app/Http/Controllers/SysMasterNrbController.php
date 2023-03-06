<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysMasterBpb;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class SysMasterNrbController extends Controller
{
    public function getDataInquiryDownloadNrb(Request $request){
        $branch_code = $request->branch_code;
        $vendor_site_code = $request->vendor_site_code;
        $tanggal_nrb_start = $request->nrb_date_start;
        $tanggal_nrb_end = $request->nrb_date_end;
        $nomor_nrb = $request->nomor_nrb;
        $offset=$request->offset;
        $limit=$request->limit;
        $skip = ($limit*$offset) - $limit;
        $return_data = array();
        $dataArray = array();
        $getData = SysMasterBpb::join('sys_ref_branch', 'sys_ref_branch.BRANCH_UNIT_CODE', '=', 'sys_master_nrb.KODE_DC')
        ->select('sys_master_nrb.ID','NO_NRB','TGL_NRB','DPP','TAX','VENDOR_SITE_CODE','INVOICE_NUM','KODE_DC');
        
        if($branch_code){
            $getData = $getData->where('sys_ref_branch.BRANCH_CODE',$branch_code);
        }
        if($vendor_site_code){
            $getData = $getData->where('sys_master_nrb.VENDOR_SITE_CODE',$vendor_site_code);
        }
        if($tanggal_bpb_start && $tanggal_bpb_end){
            $getData = $getData->whereBetween('sys_master_nrb.TGL_NRB',array($tanggal_bpb_start,$tanggal_bpb_end));
        }
        if($nomor_bpb){
            $getData = $getData->where('sys_master_nrb.NO_NRB',$nomor_bpb);
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
            $dataArray[$i]['NO_NRB'] = $a->NO_NRB;
            $dataArray[$i]['TGL_NRB'] = $a->TGL_NRB;
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
}
