<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;
use App\inventaris;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use File;

class inventarisController extends Controller
{
    public function index(Request $request)
    {
        $data_array = $this->kategori_inventaris();
        $inventaris = Inventaris::orderByRaw("FIELD(kategori, '" . implode("', '", $data_array) . "')")
        ->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $inventaris = $inventaris->where('nama', 'LIKE', '%' . request()->q . '%')
            ->orWhere('kategori', 'LIKE', '%' . request()->q . '%');
        }
        $inventaris = $inventaris->paginate(request()->per_page);

        return new UserCollection($inventaris);
    }
    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/inventaris');
         if(!File::isDirectory($path)) {
             File::makeDirectory($path, 0777, true, true);
         }
         $width = 600; // your max width
         $height = 450; // your max height

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
          'nama' => 'required',                    
        ]);     
        try {         
            $input = $request->all();  
            $foto = "";
            if($request->hasFile('foto')){             
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }  
            $input['foto'] = $foto ? $foto : 'default.png';
            inventaris::create($input);       
            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }
  

    public function edit($id)
    {
        $data = inventaris::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required'
          ]);    
          try {                                                
              $data = inventaris::find($id);  
              $foto = $data->foto;
                           
            if($request->hasFile('foto')){  
                !empty($foto) ? Storage::disk('public')->delete('/inventaris/'. $data->foto) : null;
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

    public function kondisi (Request $request, $id) {
        $data = inventaris::find($id); 
    try {  
        if($data) {
            $data->total = $request->total;
            $data->kondisi_baik = $request->kondisi_baik;
            $data->kondisi_rusak = $request->kondisi_rusak;
            $data->save();
        }
              return response()->json(['status' => 'success'], 200);   
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }

    public function destroy($id)
    {
        $data = inventaris::findOrFail($id); 
        Storage::disk('public')->delete('/inventaris/'. $data->foto);         
        $data->delete();      
        return response()->json(['status' => 'success'], 200);
    }

    public function total_inventaris ()
    {
        $total = inventaris::sum('total');
        $baik =  inventaris::sum('kondisi_baik');
        $rusak =  inventaris::sum('kondisi_rusak');
        $kategori = $this->kategori_inventaris();
        //sum kategori inventaris
        $sums = [];
        foreach ($kategori as $kat) {
            $sums[$kat] = Inventaris::where('kategori', $kat)->sum('total');
        }

        $nilai_aset = inventaris::selectRaw('SUM(harga * total) as total_seluruh')->value('total_seluruh');

        $data = [
            'total' => $total,
            'kondisi_baik' => $baik,
            'kondisi_rusak' => $rusak,
            'kategori' => $kategori,
            'sum_kategori_inventaris' => $sums,
            'nilai_aset' => $nilai_aset
        ];
        return response()->json([
            'status' => 'success',
            'data' => $data,           
        ], 200);
    }

    private function kategori_inventaris ()  
    {
        $kategori = array(
            'Operasional',
            'Alat Parkiran',
            'Perlengkapan',
            'Alat Penunjang',
        );
        return $kategori;
    }

   

}


