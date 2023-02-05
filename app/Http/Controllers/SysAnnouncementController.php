<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysAnnouncement;
use Illuminate\Support\Facades\DB;

class SysAnnouncementController extends Controller
{
    public function createAnnouncement(Request $request){
        $announcement = new SysAnnouncement();
        try{
            DB::transaction(function () use ($request){
                $announcement = SysAnnouncement::create([
                    'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                    'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                    'START_DATE' => $request->start_date,
                    'END_DATE' => $request->end_date
                ]);

            },5);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

        if($announcement){
            return response()->json([
                'status' => 'success',
                'message' => 'Announcement Berhasil dibuat!'
            ],200);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Announcement Gagal dibuat!'
            ],400);
        }
    }

    public function getDataAnnouncement(){
        $announcement = new SysAnnouncement();

        $data = $announcement->getDataAnnouncement();

        if($data){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status' => 'gagal'
            ],400);
        }
    }
}
