<?php

namespace App\Http\Controllers;

use Imagick;
use Illuminate\Http\Request;
require "../vendor/autoload.php";
use Zxing\QrReader;
class ConvertImageController extends Controller
{
    public function index(Request $request)
    {

        
        // phpinfo();
        // print_r(public_path());
        $fileone = public_path('/file_djp_ttf_idm/1674188872.pdf');
        // print_r(storage_path());
        // print_r($fileone);
        // if (!is_readable($fileone)) {
        //     echo 'file not readable';
        // }else{
        //     echo 'kebaca om';
        // }
        $imgExt = new Imagick();
        $imgExt->readImage(public_path('/file_djp_ttf_idm/1674193948.pdf'));
        $imgExt->writeImages(public_path('/file_djp_ttf_idm/Tesgambarbarcode.jpg'), true);
        dd("Document has been converted");
    }
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
  
        $fileName = time().'.'.$request->file->extension();  
   
        if($request->file->move(public_path('/file_djp_ttf_idm'), $fileName)){
            print_r("SUKSES");
        }else{
            print_r("GAGAl");
        }
        // print_r($fileName);
   
        // return back()
        //     ->with('success','You have successfully upload file.')
        //     ->with('file',$fileName);
   
    }
    public function readQr(){
        // phpinfo();
        $qrcode = new QrReader(public_path('/file_djp_ttf_idm/tesbarcodekakemma2.jpeg'));
        // print_r($qrcode);
        $text = $qrcode->text();
        // print_r("TES");
        print_r($text);
    }
}
