<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepopulatedFp extends Model
{
    use HasFactory;
    protected $table = 'prepopulated_fp';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USED_FLAG'
    ];

    public function updatePrepopulatedFP($nomor_fp,$status){
        $data = PrepopulatedFp::where('NOMOR_FAKTUR',$nomor_fp)->update([
                'USED_FLAG' => $status
        ]);

        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    public function getPrepopulatedFpByNpwp($npwp){
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->where('USED_FLAG','N')->get();

        return $data;
    }
}
