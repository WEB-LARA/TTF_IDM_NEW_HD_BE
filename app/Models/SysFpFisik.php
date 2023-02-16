<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysFpFisik extends Model
{
    use HasFactory;

    protected $table = 'sys_fp_fisik';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'FP_NUM',
        'FILENAME',
        'REAL_NAME',
        'PATH_FILE',
        'TTF_NUMBER',
        'STATUS',
        'VALIDATE_DATE',
        'CREATION_DATE',
        'LAST_UPDATE_DATE',
    ];

    public function getDataByTtfNumber($ttf_number){
        $data = SysFpFisik::where('TTF_NUMBER',$ttf_number)->get();

        return $data;
    }

    public function deleteSysFpFisik($ttf_number){

        $delete = SysFpFisik::where('TTF_NUMBER',$ttf_number)->delete();

        return $delete;
    }
}
