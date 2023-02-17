<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysFpFisik;
class SysFpFisikTempController extends Controller
{
    public function deleteSysFpFisikTempBySessId(Request $request){
        $sys_fp_fisik = new SysFpFisik();

        $delete = $sys_fp_fisik->deleteSysFpFisikTempBySessId($request->session_id);

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
