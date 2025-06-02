<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\jabatan;
class jabatanController extends Controller
{
   
    public function index(Request $request)
    {
        $jabatan = jabatan::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $jabatan = $jabatan->where('nama', 'LIKE', '%' . request()->q . '%');
        }
        $jabatan = $jabatan->get();
        return new UserCollection($jabatan);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_jabatan' => 'required',    
          'gajih_pokok' => 'required',              
        ]);     
        try {        
            $input = $request->all();                    
            jabatan::create($input);     
            return response()->json(['status' => 'success'], 200);
        
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        } 
    }
  

    public function edit($id)
    {
        $data = jabatan::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_jabatan' => 'required',  
            'gajih_pokok' => 'required',       
          ]);   
        try {                                               
              $data = jabatan::find($id);                                             
              $input = $request->all();                                       
              $data->update($input);               
               return response()->json(['status' => 'success'], 200);
                
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        } 
    }

    public function destroy($id)
    {
        $data = jabatan::findOrFail($id);        
        $data->delete();     
        return response()->json(['status' => 'success'], 200);
    }
}
