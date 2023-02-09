<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testModel extends Model
{
    use HasFactory;

    protected $table = 'ttf_data_bpb';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'BPB_ID',
        'BPB_NUMBER',
        'BPB_DATE',
    ];

    public function getdata(){
        $getdata = testModel::get();
        return $getdata;
    }
}
