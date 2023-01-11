<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysMapSupplier;
class SysMapSupplierController extends Controller
{
    protected $table = 'sys_map_supp';

    protected $primaryKey = 'ID';


    public function getSupplierByUserId(Request $request){
        $sys_map_supplier = new SysMapSupplier();

        $data = $sys_map_supplier->getSupplierByUserId($request->user_id);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
