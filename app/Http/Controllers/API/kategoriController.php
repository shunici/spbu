<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\kategori;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class kategoriController extends Controller
{

    public function index(Request $request)
    {
        $kategori = kategori::orderByRaw("FIELD(jenis, 'pengeluaran', 'pemasukan')")
        ->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $kategori = $kategori->where('nama', 'LIKE', '%' . request()->q . '%');
        }
        $kategori = $kategori->get();
        return new UserCollection($kategori);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
          'nama' => 'required', 
          'jenis' => 'required',          
        ]);    
        try {                             
            $input = $request->all();                    
            kategori::create($input);  
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
  
    }
  

    public function edit($id)
    {
        $data = kategori::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required'
          ]);   
          try {          
              $data = kategori::find($id);                                             
              $input = $request->all();                                       
              $data->update($input);               
              return response()->json(['status' => 'success'], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }

    public function destroy($id)
    {
        // $data = kategori::findOrFail($id);        
        // $data->delete();  
        $ubah_array =  explode(",",$id);
        kategori::whereIn('id', $ubah_array)->delete();

        return response()->json(['status' => 'success'], 200);
    }

}
