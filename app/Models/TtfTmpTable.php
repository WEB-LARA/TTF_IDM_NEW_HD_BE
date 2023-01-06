<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfTmpTable extends Model
{
    use HasFactory;

    protected $table='ttf_tmp_table';
    public $timestamps = false;
    protected $primaryKey = 'ID';


    public function saveToTmpTable($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag){
        $session_id = session()->getId();
        print_r($session_id);
            try{
                DB::transaction(function () use ($request,$user){
                    foreach($datga_bpb as $a){
                        $tmpTable = TtfTmpTable::create([
                            'SEQ_NUM' => 1,
                            'FP_TYPE' => $fp_type,
                            'SUPP_SITE' => '121',
                            'CABANG' => $branch_code,
                            'NO_FP' => $no_fp,
                            'NO_NPWP' => 'teest npwp',
                            'FP_DATE' => $tanggal_fp,
                            'FP_DPP' => $dpp_fp,
                            'FP_TAX' => $tax_fp,
                            'BPB_NUM' => $a['bpb_num'],
                            'BPB_DATE' => $a['bpb_date'],
                            'BPB_AMOUNT' => $a['bpb_amount'],
                            'BPB_PPN' => $a['bpb_ppn'],
                            'SESS_ID' => $session_id,
                            'SCAN_FLAG' => $scan_flag
                        ]);
                    }
    
                },5);
            }catch (\Exception $e) {

                return $e->getMessage();
            }
    }
}
