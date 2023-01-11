<?php

namespace App\Http\Controllers;

use Imagick;
use Illuminate\Http\Request;

class ConvertImageController extends Controller
{
    public function index()
    {
        phpinfo();
        // print_r(public_path());
        // $imgExt = new Imagick();
        // $imgExt->readImage('/opt/lampp/htdocs/TTF_IDM_NEW_HD_BE/storage/pdf/010.002-22.09707040.pdf');
        // $imgExt->writeImages('pdf_image_doc.png', true);
        // dd("Document has been converted");
    }
}
