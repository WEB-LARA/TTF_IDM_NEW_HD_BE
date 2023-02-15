<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    public function getSiteCodeAndBranchForValidateFlag($session_id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->groupBy('SUPP_SITE','CABANG')->select('SUPP_SITE','CABANG')->get();
        return $data;
    }
    public function getNoFpTmpBySessionIdAndNoFp($session_id,$no_fp){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->whereRaw('NO_FP = ?',[$no_fp])->select('NO_FP')->first();

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
    }
    public function validateBranchInOneFp($session_id,$no_fp,$branch){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('CABANG','<>',$branch)->where('NO_FP',$no_fp)->count();
        return $data;
    }
    public function checkSuppInFp($session_id,$no_fp,$supp_site){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('SUPP_SITE','<>',$supp_site)->where('NO_FP',$no_fp)->count();
        return $data;
    }

    public function checkSelisihFP($session_id){
        // $data = TtfUploadTmp::join('prepopulated_fp', 'prepopulated_fp.NOMOR_FAKTUR', '=', 'ttf_upload_tmp.NO_FP')
        // ->where('ttf_upload_tmp.FP_DPP','=','prepopulated_fp.JUMLAH_DPP')
        // ->where('ttf_upload_tmp.FP_TAX','=','prepopulated_fp.JUMLAH_PPN')
        // ->where('ttf_upload_tmp.SESS_ID',$session_id)
        // ->groupBy('ttf_upload_tmp.SEQ_NUM','ttf_upload_tmp.NO_FP')
        // ->select('ttf_upload_tmp.NO_FP','ttf_upload_tmp.FP_DPP','ttf_upload_tmp.FP_TAX')
        // ->selectRaw('abs(ttf_upload_tmp.FP_DPP - sum(ttf_upload_tmp.BPB_AMOUNT))  SELISIH_DPP')
        // ->selectRaw('abs(ttf_upload_tmp.FP_TAX - sum(ttf_upload_tmp.BPB_PPN)) SELISIH_PPN')
        // ->get();
        $data = DB::select('SELECT a.NO_FP,a.FP_DPP,a.FP_TAX,(a.FP_DPP + a.FP_TAX) NILAI_FP, abs(a.FP_DPP - sum(a.BPB_AMOUNT))  SELISIH_DPP, abs(a.FP_TAX - sum(a.BPB_PPN)) SELISIH_PPN from ttf_upload_tmp a,prepopulated_fp fp where a.NO_FP=fp.NOMOR_FAKTUR AND a.FP_DPP=fp.JUMLAH_DPP AND a.FP_TAX=fp.JUMLAH_PPN AND  a.SESS_ID = ? group by a.SEQ_NUM, a.NO_FP',[$session_id]);
        // print_r($data);
        return $data;
    }

    public function getSeqNumBySessionId($session_id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->groupBy('SEQ_NUM')->select('SEQ_NUM')->get();
        return $data;
    }

    public function getSumAmountForNoFp($session_id,$seq_num){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('FP_TYPE',2)->where('SEQ_NUM',$seq_num)
        ->selectRaw('sum(BPB_AMOUNT) SUM_BPB_AMOUNT')
        ->selectRaw('sum(BPB_PPN) SUM_BPB_PPN')
        ->first();
        return $data;
    }

    public function checkDataUploadTmp($session_id){
        $data = DB::select('SELECT a.NO_FP,a.FP_DPP,a.FP_TAX,(a.FP_DPP + a.FP_TAX) NILAI_FP, abs(a.FP_DPP - sum(a.BPB_AMOUNT))  SELISIH_DPP, abs(a.FP_TAX - sum(a.BPB_PPN)) SELISIH_PPN from ttf_upload_tmp a where   a.SESS_ID = ? group by a.SEQ_NUM, a.NO_FP',[$session_id]);
        return $data;
    }
    public function validateFlagGoPerFp($session_id,$no_fp){
        // $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('NO_FP',$no_fp)->groupBy('FLAG_GO','NO_FP')->count();
        $data = DB::select("SELECT 
                                COUNT(*) COUNT_DATA
                            FROM
                                (SELECT 
                                    FLAG_GO
                                FROM
                                    ttf_upload_tmp
                                WHERE
                                    SESS_ID = ?
                                        AND NO_FP = ?
                                GROUP BY NO_FP , FLAG_GO) asd",[$session_id,$no_fp]);
        return $data;
    }
    public function validateFlagPpnPerFp($session_id,$no_fp){
        // $data = TtfUploadTmp::where('SESS_ID',$session_id)->where('NO_FP',$no_fp)->groupBy('FLAG_GO','NO_FP')->count();
        $data = DB::select("SELECT 
                                COUNT(*) COUNT_DATA
                            FROM
                                (SELECT 
                                    FLAG_PPN
                                FROM
                                    ttf_upload_tmp
                                WHERE
                                    SESS_ID = ?
                                        AND NO_FP = ?
                                GROUP BY NO_FP , FLAG_PPN) asd",[$session_id,$no_fp]);
        return $data;
    }
    public function deleteTtfUploadTmpBySessId($session_id){
        $delete = TtfUploadTmp::where('SESS_ID',$session_id)->delete();

        if($delete){
            return 1;
        }else{
            return 0;
        }
    }

    public function getFPYangdiUploadBySessionId($session_id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->groupBy('NO_FP')->count();
        return $data;
    }

    public function getDataForInquiryUpload($session_id){
        $data = TtfUploadTmp::where('SESS_ID',$session_id)->groupBy('NO_FP','STATUS')->select('NO_FP','STATUS')->get();

        return $data;
    }
}
