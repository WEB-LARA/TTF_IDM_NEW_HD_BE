<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\testModel;

class testController extends Controller
{
    //
    public function testFUnction(){
        print_r("TEST");
    }

    public function get(){
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
}
