<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysUser;
class DashboardController extends Controller
{
    //
    public function getDataForInquiryTtfDashboard(Request $request){
        $sys_user = new SysUser();

        $data = $sys_user->getDataForInquiryTtfDashboard($request->id_user,$request->branch_code);
        return response()->json([
                'status' => 'success',
                'data' => $data
        ]);
    }
    
}
