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
        // ->orderBy('---')->...
        //->take(10)->...
        //->first(); //the first model matching the query constraints
        //->count(); //you may also use the count, sum, max, and other aggregate methods
        return $data;
    }

    public function joindata(){
        // $data = testModel::join('ttf_lines', 'da.id', '=', 'posts.user_id')
        //        ->get(['users.*', 'posts.descrption']);
        $data = testModel::join('ttf_lines', 'ttf_lines.TTF_BPB_ID', '=', 'ttf_data_bpb.ID')
                    ->get();
        //$data = testModel::addSelect(['last_flight' => Flight::select('name')
        // ->whereColumn('destination_id', 'destinations.id')
        // ->orderByDesc('arrived_at')
        // ->limit(1)
        // ])->get();

        return $data;
    }
    
}