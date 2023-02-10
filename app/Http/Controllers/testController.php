<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\testModel;

class testController extends Controller
{
    //
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
        $data = testModel::crossJoin('ttf_lines')->get();
        // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.ID')
        //       		->get(['ttf_data_bpb.BPB_NUMBER', 'ttf_lines.CREATION_DATE']);
        // $test_model = new testModel();
        // $data = $test_model->joindata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }
}
