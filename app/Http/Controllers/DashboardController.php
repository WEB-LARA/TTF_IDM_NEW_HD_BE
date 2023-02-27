<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysUser;
use App\Models\SysRefBranch;
use App\Models\TtfHeader;
use App\Models\SysMapSupplier;
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

        $data = $sys_user->getAllDataUser($request->role_id,$request->user_id);

        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }

    public function getAllbranch(Request $request){
        $sys_ref_branch = new SysRefBranch();
        $sys_mapp_supplier = new SysMapSupplier();

        $getBranchFromUser = $sys_mapp_supplier->getBranchByUserId($request->user_id);

        print_r($getBranchFromUser);
        $data =  $sys_ref_branch->getAllbranch($getBranchFromUser);

        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }
    
}
