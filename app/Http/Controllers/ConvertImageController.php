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
            // Convert Fp ke Gambar
            // print_r("MASOK");
            $fileNameConverted = $this->convertFpPdfToImage($fileName);
            $linkQr = '';
            // foreach ($fileNameConverted as $a){
            //     print_r($a);
            //     $linkQr .= $this->readQr($a);
            // }
            // // $expLodeFileName = explode(".",$fileNameConverted);
            // // print_r($expLodeFileName);
            // // Scan Qr Faktur Pajak
            // // $linkQr = $this->readQr($fileNameConverted);

            // $explodeLink = explode("/",$linkQr);
            // print_r($explodeLink);
        }else{
            print_r("GAGAl");
        }
        // print_r($fileName);
   
        // return back()
        //     ->with('success','You have successfully upload file.')
        //     ->with('file',$fileName);
   
    }
    
    public function convertFpPdfToImage($filename){
        $getNumberPages = new Imagick(public_path('/file_djp_ttf_idm/'.$filename));
        $numOfPages = $getNumberPages->getNumberImages();
        // print_r($numOfPages);
        // print_r($filename);
        $imgExt = new Imagick();
        $imgExt->setResolution(150,150);
        $fileNameConverted = time().'.'.'png';
        $arrayFileConverted =array ();
        $expLodeFileName = explode(".",$fileNameConverted);
        $namaFile = $expLodeFileName[0];
        $format = $expLodeFileName[1];
        $counter=  1;
        $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename.'[0]'));
        $imgExt->setImageBackgroundColor('white');
        $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imgExt->setOption('png:bit-depth', '16');
        // for($i = 0 ; $i<$numOfPages ; $i++){
        //     $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename.'['.$i.']'));
        //     $imgExt->setImageBackgroundColor('white');
        //     $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        //     $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        //     $imgExt->setOption('png:bit-depth', '16');
        //     // $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename.'[0]'));
        //     // $imgExt->setImageBackgroundColor('white');
        //     // $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        //     // $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        //     // $imgExt->setOption('png:bit-depth', '16');
        //     // $imgExt->writeImages(public_path('/file_djp_ttf_idm/'.$fileNameConverted), true);
        //     if($numOfPages>1){
        //         array_push($arrayFileConverted,$namaFile.'-'.$counter.'.'.$format);
        //     }else{
        //         array_push($arrayFileConverted,$fileNameConverted);
        //     }
        //     $counter ++;
        // }
        $imgExt->writeImages(public_path('/file_djp_ttf_idm/'.$fileNameConverted), false);

        // return $arrayFileConverted;
        // $imgExt = new Imagick();
        // $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename));
        // $fileNameConverted = time().'.'.'png';
        // $i = 0;
        // foreach($imgExt as $i=>$imgExt){
        //     $imgExt->readImage(public_path('/file_djp_ttf_idm/'.$filename.'['.$i.']'));
        //     $imgExt->setImageBackgroundColor('white');
        //     $imgExt->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        //     $imgExt->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        //     $imgExt->setOption('png:bit-depth', '16');
        //     print_r("1");
        //     echo "<br>";
        //     $imgExt->writeImages(public_path('/file_djp_ttf_idm/'.$fileNameConverted.'['.$i.']'), true);
        //     $i ++;
        // }

        // return $fileNameConverted;
    }
    public function readQr(){
        // phpinfo();
        ini_set('memory_limit', '-1');
        $qrcode = new QrReader(public_path('/file_djp_ttf_idm/1674653470.png'));
        // print_r($qrcode);
        $text = $qrcode->text();
        // print_r("TES");
        // print_r($text);
        print_r ($text);
        // return $text;
    }
}
