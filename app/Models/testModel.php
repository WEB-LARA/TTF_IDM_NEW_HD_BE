<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid; //UUIDs are universally unique alpha-numeric identifiers that are 36 characters long

class testModel extends Model
{
    use HasFactory;

    protected $table = 'ttf_data_bpb'; //specify the model's table name
    protected $primaryKey = 'ID'; //The primary key associated with the table.
    public $incrementing = false; //Indicates if the model's ID is auto-incrementing.
    //protected $keyType = 'string'; // If your model's primary key is not an integer
    public $timestamps = false; //Indicates if the model should be timestamped.
    protected $fillable = [
        'BPB_ID',
        'BPB_NUMBER',
        'BPB_DATE',
    ];

    public function getdata(){
        $data = testModel::get();
        return $data;
    }

    public function selectdata(){
        $data = testModel::where('BRANCH_CODE', '006')->take(10)->get();
        // ->orderBy('---')->take(10)->get();
        return $data;
    }
}
