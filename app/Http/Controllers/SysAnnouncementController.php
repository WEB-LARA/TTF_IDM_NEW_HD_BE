<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysAnnouncement;
use Illuminate\Support\Facades\DB;

class SysAnnouncementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    public function createAnnouncement(Request $request){
        $announcement = new SysAnnouncement();
        $files = $request->file;
        // foreach ($files as $key => $file){
        //     print_r("TEST");
        // }

        try{
            DB::transaction(function () use ($request){
                if($request->hasFile('file_pengumuman')){
                    $fileName = time().'.'.$request->file_pengumuman->extension();  
                    if($request->file_pengumuman->move(public_path('/file_pengumuman'), $fileName)){
                        // Convert Fp ke Gambar
                        $announcement = SysAnnouncement::create([
                            'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                            'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                            'START_DATE' => $request->start_date,
                            'END_DATE' => $request->end_date,
                            'FILENAME' => $fileName
                        ]);
                    }
                }else{
                        $announcement = SysAnnouncement::create([
                            'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                            'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                            'START_DATE' => $request->start_date,
                            'END_DATE' => $request->end_date
                        ]);
                }

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

    public function updateAnnouncement(Request $request){
        $announcement = new SysAnnouncement();
        $files = $request->file;
        // foreach ($files as $key => $file){
        //     print_r("TEST");
        // }

        if($request->file_pengumuman){
            $fileName = time().'.'.$request->file_pengumuman->extension();
            try{
                DB::transaction(function () use ($request,$fileName){
                    if($request->file_pengumuman->move(public_path('/file_pengumuman'), $fileName)){
                        $announcement = SysAnnouncement::where('ID_PENGUMUMAN',$request->id_pengumuman)->update([
                            'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                            'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                            'START_DATE' => $request->start_date,
                            'END_DATE' => $request->end_date,
                            'FILENAME' => $fileName
                        ]);
                    }

                },5);
            }catch (\Exception $e) {
                return $e->getMessage();
            }
        }else{
            try{
                DB::transaction(function () use ($request){
                    $announcement = SysAnnouncement::where('ID_PENGUMUMAN',$request->id_pengumuman)->update([
                        'JUDUL_PENGUMUMAN' => $request->judul_pengumuman,
                        'ISI_PENGUMUMAN' => $request->isi_pengumuman,
                        'START_DATE' => $request->start_date,
                        'END_DATE' => $request->end_date
                    ]);

                },5);
            }catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if($announcement){
            return response()->json([
                'status' => 'success',
                'message' => 'Announcement Berhasil di Update!'
            ],200);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Announcement Gagal di Update!'
            ],400);
        }
    }


    public function deletePengumuman(Request $request){
        $announcement = new SysAnnouncement(); 

        $delete = SysAnnouncement::where('ID_PENGUMUMAN',$request->id_pengumuman)->delete();

        if($announcement){
            return response()->json([
                'status' => 'success',
                'message' => 'Announcement Berhasil di Delete!'
            ],200);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Announcement Gagal di Delete!'
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
    public function getDownload(Request $request)
    {
        //PDF file is stored under project/public/download/info.pdf
        $announcement = new SysAnnouncement();
        $getFilename = $announcement->getFilename($request->id_pengumuman);
        print_r($getFilename->FILENAME);
        // $file= public_path(). "/download/info.pdf";

        // $headers = array(
        //           'Content-Type: application/pdf',
        //         );

        // return Response::download($file, 'filename.pdf', $headers);
    }
}
