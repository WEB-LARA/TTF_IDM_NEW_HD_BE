<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysUser;
use App\Models\SysRefBranch;
class DashboardController extends Controller
{
    //
    public function getDataForInquiryTtfDashboard(Request $request){
        $sys_user = new SysUser();
        $data = $sys_user->getDataForInquiryTtfDashboard($request->id_user,$request->branch_code);
        // print_r($data);
        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }

    public function getAllDataUser(){
        $sys_user = new SysUser();

        $data = $sys_user->getAllDataUser();

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
