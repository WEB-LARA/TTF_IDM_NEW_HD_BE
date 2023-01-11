<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Imagick;

class ConvertImageController extends Controller
{
    public function index()
    {
        print_r(public_path());
        // $imgExt = new Imagick();
        // $imgExt->readImage(public_path('pdf-document.pdf'));
        // $imgExt->writeImages('pdf_image_doc.jpg', true);
        // dd("Document has been converted");
    }
}
