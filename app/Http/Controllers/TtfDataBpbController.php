<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfDataBpb;

class TtfDataBpbController extends Controller
{
    public function getDataBPBPerSupplier(Request $request){
        $ttf_data_bpb = new TtfDataBpb();
        $id_bpb = array();
        if($request->data_bpb){
            foreach($request->data_bpb as $a){
                array_push($id_bpb,$a['bpb_id']);
            }
        }
        $getData = $ttf_data_bpb->getDataBPBPerSupplier($request->supp_site_code,$request->branch_code,$id_bpb,$request->session_id,$request->flag_go,$request->flag_ppn,$request->tipe_faktur,$request->jenis_faktur,$request->offset,$request->limit);
        return response()->json([
                'status' => 'success',
                'count' => $getData['count'],
                'data' => $getData['data']
            ]);
    }
}
