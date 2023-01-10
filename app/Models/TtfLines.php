<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfLines extends Model
{
    use HasFactory;

    protected $table='ttf_lines';
    public $timestamps = false;
    protected $primaryKey = 'TTF_LINE_ID';
    protected $fillable = [
        'TTF_ID',
        'TTF_BPB_ID',
        'TTF_FP_ID',
        'ACTIVE_FLAG',
        'CREATION_DATE',
        'CREATED_BY',
        'LAST_UPDATE_DATE',
        'LAST_UPDATE_BY',
        'TTF_HEADERS_TTF_ID',
        'TTF_FP_TTF_FP_ID'
    ];
}
