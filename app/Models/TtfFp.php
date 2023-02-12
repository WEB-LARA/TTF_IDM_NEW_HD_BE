<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfFp extends Model
{
    use HasFactory;

    protected $table='ttf_fp';
    public $timestamps = false;
    protected $primaryKey = 'TTF_FP_ID';
    protected $fillable = [
        'TTF_ID',
        'FP_NUM',
        'FP_TYPE',
        'FP_DATE',
        'FP_DPP_AMT',
        'FP_TAX_AMT',
        'USED_FLAG',
        'CREATED_BY',
        'CREATION_DATE',
        'LAST_UPDATE_BY',
        'LAST_UPDATE_DATE',
        'TTF_HEADERS_TTF_ID',
        'SCAN_FLAG'
    ];

    public function validateFPisUsedByFpNum($fp_number){

        $data = TtfFp::where('FP_NUM',$fp_number)->where('USED_FLAG','Y')->count();

        return $data;
    }
}
