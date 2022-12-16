<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysSupplier;


class SysSupplierController extends Controller
{
    public function getAllSupplier(){
        // $sys_supplier = new SysSupplier();

        $getAllData = SysSupplier::all();

        // print_r($getAllData);
        return response()->json([
                'status' => 'success',
                'data' => $getAllData,
            ]);
    }
}
