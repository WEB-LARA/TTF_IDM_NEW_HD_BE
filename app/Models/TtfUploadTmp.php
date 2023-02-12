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
}
