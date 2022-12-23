<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysSuppSite;
class SysSuppSiteController extends Controller
{
    public function getAllSupplierSite(Request $request){
        // $sys_supplier = new SysSupplier();

        $getAllData = SysSuppSite::where('SUPP_BRANCH_CODE',$request->branch_code)->get();

        // print_r($getAllData);
        return response()->json([
                'status' => 'success',
                'data' => $getAllData,
            ]);
    }
}
