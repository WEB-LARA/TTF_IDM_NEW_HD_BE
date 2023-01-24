<?php

namespace App\Http\Controllers;

use Imagick;
use ImagickPixel;
use Illuminate\Http\Request;
require "../vendor/autoload.php";
use Zxing\QrReader;
class ConvertImageController extends Controller
{
    public function index(Request $request)
    {
        $imgExt = new Imagick();
        $imgExt->setResolution(125,125);
        $imgExt->readImage(public_path('/file_djp_ttf_idm/1674531730.pdf'));
        $imgExt->setImageBackgroundColor('white');
        $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $imgExt->setOption('png:bit-depth', '16');
        $imgExt->writeImages(public_path('/file_djp_ttf_idm/Tesgambarbarcode3sendiri5125.png'), true);
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
        // Convert Fp ke Gambar
        $fileNameConverted = $this->convertFpPdfToImage($filename);
        // Scan Qr Faktur Pajak
        $this->readQr($fileNameConverted);
        // print_r($fileName);
   
        // return back()
        //     ->with('success','You have successfully upload file.')
        //     ->with('file',$fileName);
   
    }
    
    public function convertFpPdfToImage($filename){
        $imgExt = new Imagick();
        $imgExt->setResolution(125,125);
        $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename));
        $imgExt->setImageBackgroundColor('white');
        $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $imgExt->setOption('png:bit-depth', '16');
        $fileNameConverted = time().'.'.'png';
        $imgExt->writeImages(public_path('/file_djp_ttf_idm/'.$fileNameConverted), true);

        return $fileNameConverted;
    }
    public function readQr($filename){
        // phpinfo();
        ini_set('memory_limit', '-1');
        $qrcode = new QrReader(public_path('/file_djp_ttf_idm/'.$filename));
        // print_r($qrcode);
        $text = $qrcode->text();
        // print_r("TES");
        print_r($text);
    }
}
