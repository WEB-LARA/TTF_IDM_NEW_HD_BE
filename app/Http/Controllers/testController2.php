<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel2;

class testController2 extends Controller
{
    public function getDataInquiryLampiran(){
        $test_model2 = new testModel2();
        $data = $test_model2->getDataInquiryLampiran();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function searchDataInquiryLampiran(Request $request){
        $test_model2 = new testModel2();

        $data = $test_model2->searchDataInquiryLampiran($request->branch,$request->nottf,$request->kodesupp,$request->username,$request->tglttf_from,$request->tglttf_to,$request->status,$request->session_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
        ]);
    }
}
