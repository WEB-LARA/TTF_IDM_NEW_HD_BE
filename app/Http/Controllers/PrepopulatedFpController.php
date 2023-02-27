<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrepopulatedFp;

class PrepopulatedFpController extends Controller
{
    public function getPrepopulatedFpByNpwp(Request $request){
        $prepopulated_fp = new PrepopulatedFp();

        $data = $prepopulated_fp->getPrepopulatedFpByNpwp($request->npwp,$request->supp_site_code,$request->branch_code,$request->session_id,$request->offset,$request->limit);

        return response()->json([
                'status' => 'success',
                'count'=> $data['count'],
                'data' => $data['data'],
        ]);
    }
}
