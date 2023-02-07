<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysAnnouncement extends Model
{
    use HasFactory;

    protected $table='sys_announcement';
    public $timestamps = false;
    protected $primaryKey = 'ID_PENGUMUMAN';
    protected $fillable = [
        'JUDUL_PENGUMUMAN',
        'ISI_PENGUMUMAN',
        'START_DATE',
        'END_DATE',
        'FILENAME'
    ];

    public function getDataAnnouncement(){
        $getData = SysAnnouncement::get();

        return $getData;
    }

    public function getFilename($id){
        $getData = SysAnnouncement::where('ID_PENGUMUMAN',$id)->select('FILENAME')->get();
        return $getData;
    }
}
