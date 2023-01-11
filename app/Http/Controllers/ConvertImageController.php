<?php

namespace App\Http\Controllers;

use Imagick;
use Illuminate\Http\Request;

class ConvertImageController extends Controller
{
    public function index()
    {
        // phpinfo();
        print_r(public_path());
        $imgExt = new Imagick();
        $imgExt->readImage('/usr/src/app/010.002-22.09707040.pdf');
        $imgExt->writeImages('pdf_image_doc.png', true);
        dd("Document has been converted");
    }
}
