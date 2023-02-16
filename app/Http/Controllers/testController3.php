<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel3;

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
}
