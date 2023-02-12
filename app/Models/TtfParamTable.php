<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfParamTable extends Model
{
    use HasFactory;

    protected $table='ttf_param_table';
    public $timestamps = false;
    protected $primaryKey = 'TTF_LINE_ID';
    protected $fillable = [
        'COUNTER_TTFS',
        'COUNTER_FP',
        'COUNTER_BPB',
        'RUNNING_YEARS'
    ];

    public function getRunningYears(){
        // SELECT COUNTER_TTFS,RUNNING_YEARS,DATE_FORMAT(sysdate(),'%Y') YEAR_NOW,DATE_FORMAT(sysdate(),'%y') YEAR_USE FROM ttf_param_table
        $data = TtfParamTable::where('ID_PARAM',1)->select('COUNTER_TTFS','RUNNING_YEARS')->selectRaw('DATE_FORMAT(sysdate(),\'%Y\') YEAR_NOW')->selectRaw('DATE_FORMAT(sysdate(),\'%y\') YEAR_USE')->first();

        if($data){
            return $data;
        }else{
            return 0;
        }
    }

    public function updateRunningYears($running_year){
        $update = TtfParamTable::where('ID_PARAM',1)->update([
            'RUNNING_YEARS' => $running_year
        ]);

        if($update){
            return 1;
        }else{
            return 0;
        }
    }

    public function updateCounterTtfs($counter_ttfs){
        $update = TtfParamTable::where('ID_PARAM',1)->update([
            'COUNTER_TTFS' => $counter_ttfs
        ]);

        if($update){
            return 1;
        }else{
            return 0;
        }
    }

    public function getMaxBpbAndPpn(){
        $data = TtfParamTable::where('ID_PARAM',1)->select('MAX_SELISIH_DPP','MAX_SELISIH_TAX')->get();

        if($data){
            return $data;
        }else{
            return 0;
        }
    }

    public function getMaxSelisih(){
        $data = TtfParamTable::where('ID_PARAM',1)->selectRaw('(MAX_SELISIH_DPP + MAX_SELISIH_TAX) as MAX_SELISIH')->first();
        if($data){
            return $data;
        }else{
            return 0;
        }
    }
}
