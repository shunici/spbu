<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\laporan;
use App\Http\Resources\UserCollection;

class laporanController extends Controller
{


    
    public function index(Request $request)
    {
        $tahun = request()->tahun;
        $bulan = request()->bulan;
        $data_array = $this->jenis_laporan();
        $jenis_laporan = request()->jenis_laporan;
        $laporan = laporan::orderBy('created_at', 'DESC')->where('template', 0);
        if($bulan) {
            $laporan->whereMonth('created_at', $bulan);
        }       
        if($tahun) {
            $laporan->whereYear('created_at', $tahun);
        }   
        if ($jenis_laporan != '') {
            $laporan = $laporan->where('jenis_laporan', $jenis_laporan);
        }
        $laporan = $laporan->paginate(request()->per_page);
        return new UserCollection($laporan);
    }

    public function template(Request $request)
    {
      
        $jenis_laporan = request()->jenis_laporan;
        $laporan = laporan::orderBy('created_at', 'DESC')->where('template', 1);
        if ($jenis_laporan != '') {
            $laporan = $laporan->where('jenis_laporan', $jenis_laporan);
        }
        $laporan = $laporan->get();
        return new UserCollection($laporan);
    }



    public function store(Request $request)
    {
        $this->validate($request, [
          'judul' => 'required', 
          'jenis_laporan' => 'required', 
          'template' => 'required',          
        ]);    
        try {                             
            $input = $request->all();            
           $lapor =  laporan::create($input);  
           $slug = str::slug($request->judul) . '-' . $lapor->id;
           $lapor->update(['slug' => $slug]);
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
  
    }
  

    public function edit($id)
    {
        $data = laporan::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    
    public function show($slug)
    {
        $data = laporan::where('slug', $slug)->first();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'judul' => 'required', 
            'jenis_laporan' => 'required', 
            'template' => 'required',  
          ]);   
          try {          
              $data = laporan::find($id);                                             
              $input = $request->all();     
              $slug = str::slug($request->judul) . '-' . $data->id;                                  
              $data->update($input);               
              return response()->json(['status' => 'success'], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }

    public function destroy($id)
    {
        // $data = laporan::findOrFail($id);        
        // $data->delete();  
        $ubah_array =  explode(",",$id);
        laporan::whereIn('id', $ubah_array)->delete();

        return response()->json(['status' => 'success'], 200);
    }


    public function jenislaporan()
    {
        return $this->jenis_laporan();
    }


    private function jenis_laporan()
    {
        $namaFont = [
            " 'Arimo', sans-serif " => 'Arial',   
            "'Abril Fatface', cursive" => 'Abril Fatface',  
            " 'Bilbo Swash Caps', cursive " => 'Bilbo', 
            " 'Berkshire Swash', cursive " => 'Berkshire',
            "'Birthstone', cursive" => 'Birthstone',  
            " 'Black Han Sans', sans-serif " => 'Black',       
            "'Courgette', cursive" => 'Courgette',
            "'Carattere', cursive " => 'Carattere',
            "'Chela One', cursive " => 'Chela One', 
            "'Dancing Script', cursive" => 'Dancing Script',     
            "'Damion', cursive" => 'Damion',  
            "'Fleur De Leah', cursive" => 'Fleur De Leah',
            "'Licorice', cursive" => 'Licorice',
            "'Leckerli One', cursive" => 'Leckerli One',
            " 'Lily Script One', cursive " => 'Lily Script One', 
            "Montserrat,sans-serif" => 'Montserrat',  
            " 'Ms Madi', cursive " => 'Ms Madi',   
            "'Mr De Haviland', cursive" => 'Mr De Haviland', 
            " 'Montserrat Alternates', sans-serif " => 'Montserrat Alternates',   
            "'Norican', cursive" => 'Norican',
            "Oswald,sans-serif" => 'Oswald',
            "'Oleo Script Swash Caps', cursive" => 'Oleo Script',
            "'Oooh Baby', cursive" => 'Oooh Baby',   
            " 'Orbitron', sans-serif " => 'Orbitron',    
            "'Open Sans Condensed',sans-serif"=> 'Open Sans Condensed',
            "'Paprika', cursive" => 'Paprika',
            "'Pacifico', cursive" => 'Pacifico',  
            "'Pushster', cursive" => 'Pushster',
            "Roboto,sans-serif" => 'Roboto',
            " 'Redressed', cursive " => 'Redressed',                                
            "'Titan One', cursive" => 'Titan One',                        
            "'Tinos', serif" => 'Tinos',
            "'Yaldevi', sans-serif" => 'Yaldevi', 
            "'WindSong', cursive" => 'Windsong',                                                                
            " 'Wendy One', sans-serif " => 'Wendy One',             
          ];
        $laporan = array(
            "Laporan Saldo jum'at ",
            "Laporan Harian",
            "Laporan Penggajihan",   
            "PHBI",   
            "Permohonan Bantuan Dana",
            "SK Pengurus Masjid",
            "Kabar Duka"
        );
        
        return response()->json([
            'laporan' => $laporan,           
            'font' => $namaFont
        ], 200);
    }

}
