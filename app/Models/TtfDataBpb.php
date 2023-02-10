<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfDataBpb extends Model
{
    use HasFactory;

    protected $table = 'ttf_data_bpb';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USED_FLAG'
    ];

    public function updateDataBpb($bpb_id,$status){
        $data = TtfDataBpb::where('BPB_ID',$bpb_id)->update([
                'USED_FLAG' => $status
        ]);

        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    public function getDataBPBPerSupplier($supp_site_code,$branch_code,$notIn,$sess_id,$flag_go,$flag_ppn,$tipe_faktur){
        if($tipe_faktur == 1){
            $data = TtfDataBpb::where('VENDOR_SITE_CODE',$supp_site_code)->where('BRANCH_CODE',$branch_code)->where('USED_FLAG','N')->where('FLAG_GO',$flag_go)->where('FLAG_PPN',$flag_ppn)->whereNotin('BPB_ID',$notIn)->whereRaw('BPB_NUMBER NOT IN (SELECT 
                       BPB_NUM
                    FROM
                       ttf_tmp_table
                    WHERE
                       SUPP_SITE = ? AND CABANG = ?
                           AND SESS_ID = ?)',[$supp_site_code,$branch_code,$sess_id])->havingRaw('FLAG_GO ')->get();
        }else{
            $data = TtfDataBpb::where('VENDOR_SITE_CODE',$supp_site_code)->where('BRANCH_CODE',$branch_code)->where('USED_FLAG','N')->where('FLAG_GO',$flag_go)->where('FLAG_PPN',$flag_ppn)->whereNotin('BPB_ID',$notIn)->whereRaw('BPB_NUMBER NOT IN (SELECT 
                       BPB_NUM
                    FROM
                       ttf_tmp_table
                    WHERE
                       SUPP_SITE = ? AND CABANG = ?
                           AND SESS_ID = ?) AND BPB_TAX = 0',[$supp_site_code,$branch_code,$sess_id])->get();
        }

        return $data;
    }
}
