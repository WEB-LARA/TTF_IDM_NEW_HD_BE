<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfDataBpb;

class TtfDataBpbController extends Controller
{
    public function getDataBPBPerSupplier(Request $request){
        $ttf_data_bpb = new TtfDataBpb();
        // $getData = $ttf_data_bpb->getDataBPBPerSupplier($request->supp_site_code,$request->branch_code,$request->data_bpb);
        if($request->data_bpb){
            $id_bpb = array();
            foreach($request->data_bpb as $a){
                array_push($id_bpb,$a['bpb_id']);
            }
        }else{
            print_r("KOSONG");
        }
        // return response()->json([
        //         'status' => 'success',
        //         'data' => $getAllData,
        //     ]);
    }
}
