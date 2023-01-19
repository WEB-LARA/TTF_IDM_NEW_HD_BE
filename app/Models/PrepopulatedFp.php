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
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->where('USED_FLAG','N')->whereRaw('NO_FP NOT IN (SELECT 
                                           NOMOR_FAKTUR
                                       FROM
                                           ttf_tmp_table
                                       WHERE
                                           SUPP_SITE = ? AND CABANG = ?
                                               AND SESS_ID = ?)',[$supp_site_code,$branch_code,$session_id])->get();

        return $data;
    }
}
