<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysFpFisikTemp extends Model
{
    use HasFactory;
    protected $table = 'sys_fp_fisik_temp';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'SESSION',
        'FP_NUM',
        'FILENAME',
        'REAL_NAME',
        'PATH_FILE',
        'TTF_NUMBER',
        'VALIDATE_DATE',
        'CREATION_DATE',
        'LAST_UPDATE_DATE',
        'ADDRESS'
    ];

    public function getDataSysFpFisikTmpByNoFp($no_fp){
        $data = SysFpFisikTemp::where('FP_NUMM',$no_fp)->first();
        
        return $data;
    }

    public function deleteSysFpFisikBySessionAndFpNum($session_id,$fp_num){
        $delete = SysFpFisikTemp::where('SESSION',$session_id)->where('FP_NUM',$fp_num)->delete();

        if($delete){
            return 1;
        }else{
            return 0;
        }
    }
}
