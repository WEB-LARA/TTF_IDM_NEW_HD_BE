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

    public function getDataTempUploadDjpCsvBySessId($session_id){
        $data = TempUploadDjpCsv::where('SESSION_ID',$session_id)->select('REAL_NAME','ID')->get();

        return $data;
    }

    public function getDataTempUploadDjpCsvBySessIdForUpload($session_id){
        $data = TempUploadDjpCsv::where('SESSION_ID',$session_id)->get();

        return $data;
    }
}
