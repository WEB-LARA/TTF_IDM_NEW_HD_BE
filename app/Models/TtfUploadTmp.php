<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfUploadTmp extends Model
{
    use HasFactory;

    protected $table='ttf_upload_tmp';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'SESS_ID',
        'LINE',
        'SEQ_NUM',
        'FP_TYPE',
        'SUPP_SITE',
        'CABANG',
        'NO_FP',
        'NO_NPWP',
        'FP_DATE',
        'FP_DPP',
        'FP_TAX',
        'BPB_NUM',
        'BPB_DATE',
        'BPB_AMOUNT',
        'BPB_PPN',
        'STATUS'
    ];
    public function getTtfTmpBySessionId($session_id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->select('ID','LINE', 'FP_DATE','FP_DPP', 'FP_TAX', 'BPB_NUM', 'NO_FP','FP_TYPE','SUPP_SITE','CABANG','BPB_DATE')->selectRaw('STR_TO_DATE(FP_DATE,\'%d/%m/%Y\') as FORMAT_DATE')->get();

        return $data;
    }

    public function getSiteCodeAndBranch($session_id,$status){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('STATUS',$status)->groupBy('SUPP_SITE','CABANG')->select('SUPP_SITE','CABANG')->get();
        return $data;
    }
    public function getNoFpTmpBySessionIdAndNoFp($session_id,$no_fp){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->whereRaw('SUBSTR(NO_FP,5) = ?',[$no_fp])->select('NO_FP')->first();

        return $data;
    }
    public function checkDoubleBpbForUpload($session_id,$bpb_num,$id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('BPB_NUM',$bpb_num)->where('ID','<>',$id)->count();

        return $data;
    }
    public function validateDoubleDPP($session_id,$dpp,$no_fp){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('FP_DPP','<>',$dpp)->where('NO_FP',$no_fp)->count();
        return $data;
    }
    public function validateDoublePPN($session_id,$ppn,$no_fp){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('FP_TAX','<>',$ppn)->where('NO_FP',$no_fp)->count();
        return $data;
    }
    public function validateDoubleDate($session_id,$date,$no_fp){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('FP_DATE','<>',$date)->where('NO_FP',$no_fp)->count();
        return $data;
    }
    public function validateDoubleBPB($session_id,$no_fp,$bpb_num){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('NO_FP','<>',$no_fp)->where('BPB_NUM',$bpb_num)->count();
        return $data;
                // $statement = 'SELECT * from ttf_upload_tmp where SESS_ID = ? and BPB_NUM = ? and NO_FP <> ?';
                // $row = $this
                //     ->db
                //     ->query($statement, array(
                //     $sess_id,
                //     $a->BPB_NUM,
                //     $a->NO_FP
                // ));
    }
    public function validateBranchInOneFp($session_id,$no_fp,$branch){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('CABANG','<>',$branch)->where('NO_FP',$no_fp)->count();
        return $data;
    }
}
