<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysAnnouncement;
use Illuminate\Support\Facades\DB;

class SysAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function createAnnouncement(Request $request){
        $announcement = new SysAnnouncement();
        $files = $request->file;
        foreach ($files as $key => $file){
            print_r("TEST");
        }
        // print_r($request->file('file_pengumuman'));
        // $files = $request->file('file_pengumuman');
        // foreach($files as $file){
        //     print_r($file);
        //     echo "<br>";
        // }
        // if($request->file('file_pengumuman')){
        //     foreach($request->file('file_pengumuman') as $file){
        //         print_r($file);
        //         echo "<br>";
        //     }
        // }
        // $fileName = time().'.'.$request->file->extension();  
   
        // try{
        //     DB::transaction(function () use ($request){
        //         if($request->file->move(public_path('/file_pengumuman'), $fileName)){
        //             // Convert Fp ke Gambar
        //             $announcement = SysAnnouncement::create([
        //                 'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
        //                 'ISI_PENGUMUMAN' => $request->isi_pengumuman,
        //                 'START_DATE' => $request->start_date,
        //                 'END_DATE' => $request->end_date
        //             ]);
        //         }

        //     },5);
        // }catch (\Exception $e) {
        //     return $e->getMessage();
        // }

        // if($announcement){
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Announcement Berhasil dibuat!'
        //     ],200);
        // }else{
        //     return response()->json([
        //         'status' => 'gagal',
        //         'message' => 'Announcement Gagal dibuat!'
        //     ],400);
        // }
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
