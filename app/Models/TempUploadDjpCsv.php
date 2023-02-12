<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUploadDjpCsv extends Model
{
    use HasFactory;

    protected $table = 'temp_upload_djp_csv';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'PATH_FILE',
        'FILE_NAME',
        'REAL_NAME',
        'SESSION_ID'
    ];
}
