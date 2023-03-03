<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysMasterBpb;

class SysMasterBpbController extends Controller
{
    public function getDataInquiryDownloadBpb(Request $request){
        $getData = SysMasterBpb::join('sys_ref_branch', 'sys_ref_branch.BRANCH_UNIT_CODE', '=', 'sys_master_bpb.KODE_DC')->select('NO_BPB','TGL_BPB','DPP','TAX','VENDOR_SITE_CODE','INVOICE_NUM','KODE_DC')->get();

        return response()->json([
                'status' => 'success',
                'data' => $getData,
            ]);
    }


}
