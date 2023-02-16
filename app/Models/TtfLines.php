<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function getDataBpbByTtfId($ttf_id){
        $data = DB::select("SELECT 
                                BPB_NUMBER, BPB_DPP, BPB_TAX
                            FROM
                                ttf_lines a,
                                ttf_data_bpb b
                            WHERE
                                a.TTF_BPB_ID = b.BPB_ID
                                    AND a.TTF_ID = ?",[$ttf_id]);
        return $data;
    }
}
