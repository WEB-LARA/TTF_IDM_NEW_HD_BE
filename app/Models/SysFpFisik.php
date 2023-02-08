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
}