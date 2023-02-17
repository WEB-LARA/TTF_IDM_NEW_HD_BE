<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfLampiran;

class TtfLampiranController extends Controller
{
    public function getDataTtfLampiranByTTfID(Request $request){
        $ttf_lampiran = new TtfLampiran();
        $dataLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID($request->ttf_id);

        return response()->json([
                'status' => 'success',
                'data' => $dataLampiran,
            ]);
    }
}
