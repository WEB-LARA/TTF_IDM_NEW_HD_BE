<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysUser;
use App\Models\SysRefBranch;
use App\Models\TtfHeader;
class DashboardController extends Controller
{
    //
    public function getDataForInquiryTtfDashboardUser(Request $request){
        $sys_user = new SysUser();
        $data = $sys_user->getDataForInquiryTtfDashboardUser($request->id_user,$request->branch_code);
        // print_r($data);
        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }

    public function getDataForInquiryTtfDashboard(Request $request){
        $ttf_header = new TtfHeader();
        $data = $ttf_header->getDataForInquiryTtfDashboard($request->id_user,$request->branch_code);
        // print_r($data);
        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }

    public function getAllDataUser(Request $request){
        $sys_user = new SysUser();

        $data = $sys_user->getAllDataUser($request->branch_code);

        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }

    public function getAllbranch(){
        $sys_ref_branch = new SysRefBranch();

        $data =  $sys_ref_branch->getAllbranch();

        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }
    
}
