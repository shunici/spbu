<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\realisasi;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use App\Http\Resources\UserCollection;
class realisasiController extends Controller
{
    
    public function index(Request $request)
    {
        $tahun = request()->tahun;
        $bulan = request()->bulan;

        $realisasi = realisasi::orderBy('tgl', 'ASC');      
        if($bulan) {
            $realisasi->whereMonth('tgl', $bulan);
        }       
        if($tahun) {
            $realisasi->whereYear('tgl', $tahun);
        }

        $realisasi = $realisasi->get();

        return new UserCollection($realisasi);
     
      
    }
    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/realisasi');
         if(!File::isDirectory($path)) {
             File::makeDirectory($path, 0777, true, true);
         }
         $width = 800; // your max width
         $height = 650; // your max height

         $img = Image::make($foto); //kurangi resolusi
         $img->height() > $img->width() ? $width=null : $height=null;
         $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
         $img->save($path. '/' .$images);                     
     return $images;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'toko' => 'required', 
          'qty' => 'required',                    
          'nominal' => 'required', 
        ]);     
        try {         
            $input = $request->all();  
            $foto = "";
            if($request->hasFile('foto')){             
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }  
            $input['foto'] = $foto ? $foto : 'default.png';
            realisasi::create($input);       
            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }
  

    public function edit($id)
    {
        $data = realisasi::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'toko' => 'required', 
            'qty' => 'required',                    
            'nominal' => 'required', 
          ]);    
          try {                                                
              $data = realisasi::find($id);  
              $foto = $data->foto;
                           
            if($request->hasFile('foto')){  
                !empty($foto) ? Storage::disk('public')->delete('/realisasi/'. $data->foto) : null;
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }                                                          
              $input = $request->all();    
              $input['foto'] = $foto;                                     
              $data->update($input);               
             return response()->json(['status' => 'success'], 200);
             
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }


    public function destroy($id)
    {
        $data = realisasi::findOrFail($id); 
        Storage::disk('public')->delete('/realisasi/'. $data->foto);         
        $data->delete();      
        return response()->json(['status' => 'success'], 200);
    }

}
