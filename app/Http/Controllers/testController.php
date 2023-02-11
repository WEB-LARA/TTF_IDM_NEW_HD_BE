<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel;

class testController extends Controller
{
    //
    public function testFunction(){
        print_r("TEST");
    }

    public function getdata(){
        $test_model = new testModel();
        $data = $test_model->getdata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function selectdata(){
        $test_model = new testModel();
        $data = $test_model->selectdata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function joindata(){
        // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
        //         ->get();
        $data = testModel::crossJoin('ttf_lines')
            ->limit(3)
            ->get();
        // $data = DB::table('ttf_data_bpb')
        //     ->crossJoin('ttf_lines')
        //     ->limit(3)
        //     ->get();
        // $data = testModel::crossJoin('ttf_lines')->get();
        // $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.ID')
        //       		->get(['ttf_data_bpb.BPB_NUMBER', 'ttf_lines.CREATION_DATE']);
        // $test_model = new testModel();
        // $data = $test_model->joindata();

        return response()->json([
            'status' => 'OK',
            'data' => $data
        ]);

        // if($data == 0){
        //     return response()->json([
        //         'status' => 'GAGAL',
        //         'message' => 'Gagal Mengambil Data',
        //     ],400);
        // }else{
        //     return response()->json([
        //         'status' => 'OK',
        //         'data' => $data
        //     ],200);
        // }
    }

    public function filterdata(Request $request){
        $test_model = new testModel();

        $data = $test_model->filterdata($request->branch,$request->nobpb,$request->tglbpb,$request->nottf,$request->nofp,$request->session_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
        ]);
    }

    public function inquirydata(){
        $test_model = new testModel();
        $data = $test_model->inquirydata();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
        // $data = testModel::join('ttf_fp', 'ttf_fp.TTF_FP_ID', '=', 'ttf_data_bpb.BPB_ID')
        //       ->join('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_fp.TTF_ID')
        //       ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_fp.TTF_ID')
        //       ->get(['ttf_data_bpb.VENDOR_SITE_CODE','ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE','ttf_headers.TTF_STATUS','sys_supplier.SUPP_NAME']);

    //     $data = DB::table('ttf_data_bpb')
    //           ->join('ttf_fp', 'ttf_fp.TTF_FP_ID', '=', 'ttf_data_bpb.BPB_ID')
    //           ->join('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_fp.TTF_ID')
    //           ->join('sys_supplier', 'sys_supplier.SUPP_ID', '=', 'ttf_fp.TTF_ID')
    //           ->select('ttf_data_bpb.VENDOR_SITE_CODE','ttf_data_bpb.BPB_NUMBER','ttf_data_bpb.BPB_DATE','ttf_data_bpb.BPB_DPP','ttf_data_bpb.BPB_TAX','ttf_fp.FP_NUM','ttf_fp.FP_DATE','ttf_fp.FP_DPP_AMT','ttf_fp.FP_TAX_AMT','ttf_headers.TTF_NUM','ttf_headers.TTF_DATE','ttf_headers.TTF_RETURN_DATE','ttf_headers.TTF_STATUS','sys_supplier.SUPP_NAME')
    //           ->get();

    //     return response()->json([
    //         'status' => 'OK',
    //         'data' => $data
    //     ]);
    // }
}
