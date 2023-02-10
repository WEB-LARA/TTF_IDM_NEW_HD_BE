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
        $test_model = new testModel();
        $data = $test_model->joindata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
