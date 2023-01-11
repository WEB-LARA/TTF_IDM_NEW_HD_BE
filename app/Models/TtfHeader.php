<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfHeader extends Model
{
    use HasFactory;

    protected $table='ttf_headers';
    public $timestamps = false;
    protected $primaryKey = 'TTF_ID';
    protected $fillable = [
        'BRANCH_CODE',
        'VENDOR_SITE_CODE',
        'TTF_NUM',
        'TTF_DATE',
        'TTF_TYPE',
        'TTF_STATUS',
        'TTF_RETURN_DATE',
        'TTF_GIRO_DATE',
        'ORG_ID',
        'SOURCE',
        'REVIEWED_BY',
        'REVIEWED_DATE',
        'CREATED_BY',
        'CREATION_DATE',
        'LAST_UPDATE_DATE',
        'LAST_UPDATE_BY',
        'MEMO_NUM',
        'JUMLAH_FP',
        'SUM_DPP_FP',
        'SUM_TAX_FP',
        'JUMLAH_BPB',
        'SUM_DPP_BPB',
        'SUM_TAX_BPB',
        'SELISIH_DPP',
        'SELISIH_TAX'
    ];


    public function getDataInquiryTTF($branch_code,$supp_site_code){
        $getData = TtfHeader::where('BRANCH_CODE',$branch_code)
                             ->where('VENDOR_SITE_CODE',$supp_site_code)
                             ->select('TTF_NUM','BRANCH_CODE','TTF_DATE','REVIEWED_DATE','VENDOR_SITE_CODE')
                             ->selectRaw('CASE
                                              WHEN TTF_STATUS = \'\' THEN \'DRAFT\'
                                          END AS TTF_STATUS')
                             ->get();
        return $getData;
    }

    public function getDataInquiryDetailTTF($ttf_id){
        $getData = DB::select('SELECT 
                                   asd.JUMLAH_FP,
                                   asd.JUMLAH_DPP_FAKTUR,
                                   asd.JUMLAH_PPN_FAKTUR,
                                   asd.JUMLAH_BPB,
                                   asd.JUMLAH_DPP_BPB,
                                   asd.JUMLAH_TAX_BPB,
                                   (asd.JUMLAH_DPP_FAKTUR - asd.JUMLAH_DPP_BPB) AS SEL_DPP,
                                   (asd.JUMLAH_PPN_FAKTUR - asd.JUMLAH_TAX_BPB) AS SEL_PPN
                               FROM
                                   (SELECT 
                                       COUNT(*) JUMLAH_FP,
                                           SUM(FP_DPP_AMT) JUMLAH_DPP_FAKTUR,
                                           SUM(FP_TAX_AMT) JUMLAH_PPN_FAKTUR,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_lines
                                               WHERE
                                                   TTF_ID = ?) AS JUMLAH_BPB,
                                           (SELECT 
                                                   SUM(BPB_DPP)
                                               FROM
                                                   ttf_data_bpb
                                               WHERE
                                                   BPB_ID IN (SELECT 
                                                           TTF_BPB_ID
                                                       FROM
                                                           ttf_lines
                                                       WHERE
                                                           TTF_ID = ?)) AS JUMLAH_DPP_BPB,
                                           (SELECT 
                                                   SUM(BPB_TAX)
                                               FROM
                                                   ttf_data_bpb
                                               WHERE
                                                   BPB_ID IN (SELECT 
                                                           TTF_BPB_ID
                                                       FROM
                                                           ttf_lines
                                                       WHERE
                                                           TTF_ID = ?)) AS JUMLAH_TAX_BPB
                                   FROM
                                       ttf_fp
                                   WHERE
                                       TTF_ID = ?) asd',[$ttf_id,$ttf_id,$ttf_id,$ttf_id]);
        return $getData;
    }
}
