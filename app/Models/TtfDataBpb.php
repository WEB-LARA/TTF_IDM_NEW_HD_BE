<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfDataBpb extends Model
{
    use HasFactory;

    protected $table = 'ttf_data_bpb';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USED_FLAG'
    ];

    public function updateDataBpb($bpb_id,$status){
        $data = TtfDataBpb::where('BPB_ID',$bpb_id)->update([
                'USED_FLAG' => $status
        ]);

        if($data){
            return 1;
        }else{
            return 0;
        }
    }
}