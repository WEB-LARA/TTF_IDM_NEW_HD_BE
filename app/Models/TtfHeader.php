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
                             ->select('TTF_NUMM')
                             ->selectRaw('CASE
                                              WHEN TTF_STATUS = \'\' THEN \'DRAFT\'
                                          END AS TTF_STATUS')
                             ->select('BRANCH_CODE','TTF_DATE','REVIEWED_DATE','VENDOR_SITE_CODE')
                             ->get();
        return $getData;
    }
}
