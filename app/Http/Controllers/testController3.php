<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel3;
use App\Models\testModel4;

class testController3 extends Controller
{
    public function getDataBranch(){
        $test_model3= new testModel3();
        $data = $test_model3->getDataBranch();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getDataSupplierbyBranch(Request $request){
        $test_model3= new testModel4();
        $data = $test_model3->getDataSupplierbyBranch($request->branch);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

}
