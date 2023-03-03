<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysMasterBpb extends Model
{
    use HasFactory;

    protected $table = 'sys_master_bpb';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    // protected $fillable = [
    //     'SESSION',
    //     'FP_NUM',
    //     'FILENAME',
    //     'REAL_NAME',
    //     'PATH_FILE',
    //     'TTF_NUMBER',
    //     'VALIDATE_DATE',
    //     'CREATION_DATE',
    //     'LAST_UPDATE_DATE',
    //     'ADDRESS'
    // ];


}
