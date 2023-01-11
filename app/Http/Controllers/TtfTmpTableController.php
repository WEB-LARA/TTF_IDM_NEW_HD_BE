<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfTmpTable;

class TtfTmpTableController extends Controller
{
    public function getDataTmpTtfBySuppCodeAndBranch(Request $request){
        $ttf_tmp_table = new TtfTmpTable();
        $data = $ttf_tmp_table->getDataTmpTtfBySuppCodeAndBranch($request->supp_site_code,$request->branch_code);
        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }
}
