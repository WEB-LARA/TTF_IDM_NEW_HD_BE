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
        // $request->validate([
        //     'file' => 'required|mimes:pdf|max:2048',
        // ]);
  
        $fileName = $request->file->hashName();  
        $real_name = $request->file->getClientOriginalName();
        if($request->file->move(public_path('/file_temp_fp'), $fileName)){
            // Convert Fp ke Gambar
            $fileNameConverted = $this->convertFpPdfToImage($fileName);
            $linkQr = '';
            // foreach ($fileNameConverted as $a){
            //     $linkQr .= $this->readQr($a);
            // }
            $linkQr .= $this->readQr($fileNameConverted);
            $explodeLink = explode("/",$linkQr);
            $npwp_penjual = substr($explodeLink[5], 0, 2) .
                "." .
                substr($explodeLink[5], 2, 3) .
                "." .
                substr($explodeLink[5], 5, 3) .
                "." .
                substr($explodeLink[5], 8, 1) .
                "-" .
                substr($explodeLink[5], 9, 3) .
                "." .
                substr($explodeLink[5], 12, 3);
            $no_faktur =
                substr($explodeLink[6], 0, 3) .
                "-" .
                substr($explodeLink[6], 3, 2) .
                "." .
                substr($explodeLink[6], 5, 8);
            if($request->no_npwp == $npwp_penjual && substr($request->no_faktur, 4) == $no_faktur){
                // unlink(public_path('/file_temp_fp/'.$fileName));
                unlink(public_path('/file_temp_fp/'.$fileNameConverted));
                return response()->json([
                        'status' => 'success',
                        'message' => 'validated',
                        'nama_file'=> $fileName,
                        'real_name'=> $real_name
                    ]);
            }else{
                // unlink(public_path('/file_temp_fp/'.$fileName));
                unlink(public_path('/file_temp_fp/'.$fileNameConverted));
                return response()->json([
                        'status' => 'success',
                        'message' => 'rejected',
                    ]);
            }
        }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'gagal membaca file',
                ]);
        }
   
    }
    
    public function convertFpPdfToImage($filename){
        $getNumberPages = new Imagick(public_path('/file_temp_fp/'.$filename));
        $numOfPages = $getNumberPages->getNumberImages();
        $imgExt = new Imagick();
        if($numOfPages > 1){
            $imgExt->setResolution(150,150);
        }else{
            $imgExt->setResolution(125,125);
        }
        // Set Resolusi harus 150 x 150
        // $imgExt->setResolution(150,150);
        $fileNameConverted = time().'.'.'png';
        $arrayFileConverted =array ();
        $expLodeFileName = explode(".",$fileNameConverted);
        $namaFile = $expLodeFileName[0];
        $format = $expLodeFileName[1];
        $counter=  1;
        $indexBarcode = $numOfPages - 1;
        $imgExt->readImage(public_path('/file_temp_fp/'.$filename.'['.$indexBarcode.']'));
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
        //     if($numOfPages>1){
        //         array_push($arrayFileConverted,$namaFile.'-'.$counter.'.'.$format);
        //     }else{
        //         array_push($arrayFileConverted,$fileNameConverted);
        //     }
        //     $counter ++;
        // }
        $imgExt->writeImages(public_path('/file_temp_fp/'.$fileNameConverted), false);

        return $fileNameConverted;
    }
    public function readQr($filename){
        ini_set('memory_limit', '-1');
        $qrcode = new QrReader(public_path('/file_temp_fp/'.$filename));
        $text = $qrcode->text();
        return $text;
    }

    public function createDirectory(){
        $year = date('Y');
        $dir = public_path('/file_temp_fp/'.$year);
        if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
            mkdir( $dir );     
            chmod($dir, 0777);
            print_r("TIDAK ADA");  
        }else{
            print_r("ADA");
        }         
    }
}
