<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\barang;
class barangController extends Controller
{
  
    public function index(Request $request)
    {
        $barang = barang::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $barang = $barang->where('nama', 'LIKE', '%' . request()->q . '%');
        }
        $barang = $barang->paginate(request()->per_page);
        return new UserCollection($barang);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
          'nama' => 'required',  
          'satuan' => 'required',                  
        ]);    
        try {                             
            $input = $request->all();                    
            barang::create($input);  
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
  
    }
  

    public function edit($id)
    {
        $data = barang::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required'
          ]);   
          try {          
              $data = barang::find($id);                                             
              $input = $request->all();                                       
              $data->update($input);               
              return response()->json(['status' => 'success'], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }

    public function destroy($id)
    {
        // $data = barang::findOrFail($id);        
        // $data->delete();  
        $ubah_array =  explode(",",$id);
        barang::whereIn('id', $ubah_array)->delete();

        return response()->json(['status' => 'success'], 200);
    }

}
