<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aplikasi;
class HomeController extends Controller
{
   
    public function index(Request $request)
    {
      
        $path = request()->path();
        $data = aplikasi::latest()->first();
        $meta = [
            "title" => $data->nama,
            "description" => "Sistem Informasi Manajemen Masjid",
            "url" => 'https://masjid-nurul-iman.site',
            "image" => 'https://masjid-nurul-iman.site'. '/storage/aplikasi/'. $data->logo   
        ];                               
    
        return view('index', ['meta' => $meta]);
    }
}
