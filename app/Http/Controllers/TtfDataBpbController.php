<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfDataBpb;

class TtfDataBpbController extends Controller
{
    public function getDataBPBPerSupplier(Request $request){
        $ttf_data_bpb = new TtfDataBpb();
        // $getData = $ttf_data_bpb->getDataBPBPerSupplier($request->supp_site_code,$request->branch_code,$request->data_bpb);
        print_r($request->data_bpb);
        // return response()->json([
        //         'status' => 'success',
        //         'data' => $getAllData,
        //     ]);
    }
}
