<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfLampiran extends Model
{
    use HasFactory;

    protected $table = 'ttf_lampiran';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'TTF_ID',
        'PATH_FILE',
        'FILE_SIZE',
        'UPDATED_DATE',
        'REAL_NAME'
    ];
}
