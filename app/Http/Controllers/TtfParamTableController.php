<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfParamTable;
class TtfParamTableController extends Controller
{
    public function getMaxBpbAndPpn(){
        $ttf_param_table = new TtfParamTable();

        $geData = $ttf_param_table->getMaxBpbAndPpn();

        return response()->json([
                'status' => 'success',
                'data' => $geData
            ]);
    }
}
