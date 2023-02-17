<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysFpFisikTemp;
class SysFpFisikTempController extends Controller
{
    public function deleteSysFpFisikTempBySessId(Request $request){
        $sys_fp_fisik_temp = new SysFpFisikTemp();

        $delete = $sys_fp_fisik_temp->deleteSysFpFisikTempBySessId($request->session_id);

        if($delete){
            return response()->json([
                'status' => 'success',
                'message' => 'Fp Fisik temp Berhasil dihapus!'
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Fp Fisik temp Gagal dihapus!'
            ]);
        }
    }
}
