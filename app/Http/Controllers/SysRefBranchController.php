<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysRefBranch;

class SysRefBranchController extends Controller
{
    public function getAllBranch(){
        // $sys_supplier = new SysSupplier();

        $getAllData = SysRefBranch::all();

        // print_r($getAllData);
        return response()->json([
                'status' => 'success',
                'data' => $getAllData,
            ]);
    }
}
