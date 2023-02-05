<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysAnnouncement;

class SysAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function createAnnouncement(Request $request){
        $announcement = new SysAnnouncement();
        try{
            DB::transaction(function () use ($request,$user){
                $announcement = SysAnnouncement::create([
                    'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                    'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                    'START_DATE' => $request->start_date,
                    'END_DATE' => Hash::make($request->end_date)
                ]);

            },5);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

        if($announcement){
            return response()->json([
                'status' => 'success',
                'message' => 'User Berhasil dibuat!'
            ],200);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'User Gagal dibuat!'
            ],400);
        }
    }
}
