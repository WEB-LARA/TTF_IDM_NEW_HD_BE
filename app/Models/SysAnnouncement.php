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
        $getData = SysAnnouncement::orderBy('START_DATE','DESC')->get();

        return $getData;
    }

    public function getFilename($id){
        $getData = SysAnnouncement::where('ID_PENGUMUMAN',$id)->select('FILENAME')->first();
        return $getData;
    }

    public function getDataAnnouncementInquiry($offset,$limit){
        $skip = ($limit*$offset) - $limit;
        $dataArray = array();
        $getData = SysAnnouncement::orderBy('START_DATE','DESC');

        $data_count = $getData->count();
        $data = $getData->skip($skip)->take($limit)->get();
        $i = 0;
        $nomor = $skip+1;
        foreach ($data as $a){
            // print_r($a->FP_TYPE);
            // $dataFp = $ttf_fp->getFpByTtfId($request->ttf_id);
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['ID_PENGUMUMAN'] = $a->ID_PENGUMUMAN;
            $dataArray[$i]['JUDUL_PENGUMUMAN'] = $a->JUDUL_PENGUMUMAN;
            $dataArray[$i]['ISI_PENGUMUMAN'] = $a->ISI_PENGUMUMAN;
            $dataArray[$i]['START_DATE'] = $a->START_DATE;
            $dataArray[$i]['END_DATE'] = $a->END_DATE;
            $dataArray[$i]['FILENAME'] = $a->FILENAME;
            $i++;
            $nomor++;
        }
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;
        return $return_data;
    }
}
