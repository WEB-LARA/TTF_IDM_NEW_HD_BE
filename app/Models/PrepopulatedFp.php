<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepopulatedFp extends Model
{
    use HasFactory;
    protected $table = 'prepopulated_fp';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USED_FLAG'
    ];

    public function updatePrepopulatedFP($nomor_fp,$status){
        $data = PrepopulatedFp::where('NOMOR_FAKTUR',$nomor_fp)->update([
                'USED_FLAG' => $status
        ]);

        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    public function getPrepopulatedFpByNpwp($npwp,$supp_site_code,$branch_code,$session_id){
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->where('USED_FLAG','N')->whereRaw('NOMOR_FAKTUR NOT IN (SELECT 
                                           NO_FP
                                       FROM
                                           ttf_tmp_table
                                       WHERE
                                           SUPP_SITE = ? AND CABANG = ?
                                               AND SESS_ID = ?)',[$supp_site_code,$branch_code,$session_id])->get();

        return $data;
    }
    public function getPrepopulatedFpByNoFpAndNpwp($npwp,$no_faktur){
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->whereRaw('SUBSTR(nomor_faktur,5,18) = ?',[$no_faktur])->count();

        return $data;
    }

    public function checkPrepopulatedFPByNoFakturAndUsedFlag($no_faktur){
        $data = PrepopulatedFp::whereRaw('SUBSTR(nomor_faktur,5,18) = ?',[$no_faktur])->where('USED_FLAG','N')->count();
        return $data;
    }
}
