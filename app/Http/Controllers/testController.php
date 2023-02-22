<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel;

class testController extends Controller
{
    public function testFunction(){
        print_r("TEST");
    }

    public function getdata(){
        $test_model = new testModel();
        $data = $test_model->getdata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function selectdata(){
        $test_model = new testModel();
        $data = $test_model->selectdata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function joindata(){
        // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
        //         ->get();
        $data = testModel::crossJoin('ttf_lines')
            ->limit(3)
            ->get();
        // $data = DB::table('ttf_data_bpb')
        //     ->crossJoin('ttf_lines')
        //     ->limit(3)
        //     ->get();
        // $data = testModel::crossJoin('ttf_lines')->get();
        // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.ID')
        //       		->get(['ttf_data_bpb.BPB_NUMBER', 'ttf_lines.CREATION_DATE']);
        // $test_model = new testModel();
        // $data = $test_model->joindata();

        return response()->json([
            'status' => 'OK',
            'data' => $data
        ]);
    }

    // public function getDataInquiryTtf(){
    //     $test_model = new testModel();
    //     $data = $test_model->getDataInquiryTtf();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $data
    //     ]);
    // }

    public function searchDataTtf(Request $request){
        $test_model = new testModel();

        $data = $test_model->searchDataTtf($request->branch,$request->nobpb,$request->tglbpb_from,$request->tglbpb_to,$request->nottf,$request->nofp,$request->session_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
        ]);
    }

    public function reportTtfs(Request $request){
        $test_model = new testModel();

        $data = $test_model->reportTtfs($request->id, $request->branch, $request->session_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
        ]);
    }
}
