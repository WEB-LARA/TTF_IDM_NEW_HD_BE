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

    public function getDataBPBPerSupplier($supp_site_code,$branch_code,$notIn,$sess_id,$flag_go,$flag_ppn,$tipe_faktur,$jenis_faktur,$offset,$limit){
        if($tipe_faktur == 1){
            if($jenis_faktur == '010'){
                $data = TtfDataBpb::where('VENDOR_SITE_CODE',$supp_site_code)->where('BRANCH_CODE',$branch_code)->where('USED_FLAG','N')->where('FLAG_GO',$flag_go)->where('FLAG_PPN',$flag_ppn)->whereNotin('BPB_ID',$notIn)->whereRaw('BPB_NUMBER NOT IN (SELECT 
                           BPB_NUM
                        FROM
                           ttf_tmp_table
                        WHERE
                           SUPP_SITE = ? AND CABANG = ?
                               AND SESS_ID = ?)',[$supp_site_code,$branch_code,$sess_id])->whereRaw('BPB_TAX <> 0')->get();
            }else{
                $data = TtfDataBpb::where('VENDOR_SITE_CODE',$supp_site_code)->where('BPB_TAX',0)->where('BRANCH_CODE',$branch_code)->where('USED_FLAG','N')->where('FLAG_GO',$flag_go)->where('FLAG_PPN',$flag_ppn)->whereNotin('BPB_ID',$notIn)->whereRaw('BPB_NUMBER NOT IN (SELECT 
                           BPB_NUM
                        FROM
                           ttf_tmp_table
                        WHERE
                           SUPP_SITE = ? AND CABANG = ?
                               AND SESS_ID = ?)',[$supp_site_code,$branch_code,$sess_id])->get();
            }
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

    public function getDataBpbByNoBPB($bpb_number){
        $data = TtfDataBpb::where('BPB_NUMBER',$bpb_number)->whereRaw('BPB_NUMBER IS NOT NULL')->whereRaw('VENDOR_SITE_ID <> 0')->first();

        return $data;
    }

    public function validateCountBPBByBPBNumber($bpb_number){
        // $statement = "SELECT tdb.BPB_ID from ttf_data_bpb tdb, ttf_lines tl, ttf_headers th where tdb.BPB_ID = tl.TTF_BPB_ID and tl.TTF_ID = th.TTF_ID and th.TTF_STATUS not in (?, ?) and tdb.BPB_NUMBER = ? and tdb.USED_FLAG = ?";

        $data = TtfDataBpb::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.BPB_ID')
              ->join('ttf_headers', 'ttf_headers.TTF_ID', '=', 'ttf_lines.TTF_ID')
              ->whereNotIn('ttf_headers.TTF_STATUS',['R','C'])
              ->where('ttf_data_bpb.USED_FLAG','Y')
              ->where('ttf_data_bpb.BPB_NUMBER',$bpb_number)
              ->select('ttf_data_bpb.BPB_ID')->count();
        return $data;
    }
}
