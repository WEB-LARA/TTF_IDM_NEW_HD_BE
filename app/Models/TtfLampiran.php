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

    public function getDataTtfLampiranByTTfID($ttf_id){
        $data = TtfLampiran::where('TTF_ID',$ttf_id)->get();

        return $data;
    }

    public function deleteTtfLampiran($ttf_id){
        $delete = TtfLampiran::where('TTF_ID',$ttf_id)->delete();

        if($delete){
            return 1;
        }else{
            return 0;
        }
    }

    public function checkDataExistsOnTtfLampiran($ttf_id){
        $data = TtfLampiran::where('TTF_ID',$ttf_id)->count();

        return $data;
    }
}
