<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use App\sponsor;
class sponsorController extends Controller
{
 
    public function index(Request $request)
    {
        
        $sponsor = sponsor::orderByRaw("FIELD(posisi, 'sidebar', 'footer')")
        ->orderBy('created_at', 'asc');
        if (request()->q != '') {
            $sponsor = $sponsor->where('nama', 'LIKE', '%' . request()->q . '%');
            // ->orWhere('kategori', 'LIKE', '%' . request()->q . '%');
        }
        $sponsor = $sponsor->get();

        return new UserCollection($sponsor);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama' => 'required',   
          'jumlah' => 'required',   
          'status' => 'required', 
          'posisi' => 'required', 
          'tanggal_mulai' => 'required',
          'tanggal_berakhir' => 'required'
        ]);     
        try {         
            $input = $request->all();  
            $logo = "";
            if($request->hasFile('logo')){             
                $logo =  $this->simpan_file( $request->file('logo') ); 
            }  
            $input['logo'] = $logo ? $logo : 'default.png';
            sponsor::create($input);       
            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }
  

    public function edit($id)
    {
        $data = sponsor::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    
    public function show($id)
    {
        $data = sponsor::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [      
            'nama' => 'required',   
            'jumlah' => 'required',   
            'status' => 'required', 
            'posisi' => 'required',             
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required'
          ]);    
          try {                                                
              $data = sponsor::find($id);  
              $logo = $data->logo;
                           
            if($request->hasFile('logo')){  
                !empty($logo) ? Storage::disk('public')->delete('/sponsor/'. $data->logo) : null;
                $logo =  $this->simpan_file( $request->file('logo') ); 
            }                                                          
              $input = $request->all();    
              $input['logo'] = $logo;                                     
              $data->update($input);               
             return response()->json(['status' => 'success'], 200);
             
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }

    public function destroy($id)
    {
        $data = sponsor::findOrFail($id);        
        $data->delete();      
        return response()->json(['status' => 'success'], 200);
    }

    
    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/sponsor');
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

    
    
    public function upload(Request $request)
    {
      if ($request->hasFile('file')) {
        $image = $request->file('file');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/sponsorItem', $imageName);

        $imageUrl = asset('/storage/sponsorItem/' . $imageName);
        return response()->json(['link' => $imageUrl]);
    }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function deleteImage(Request $request)
    {
  
      $filePath = 'sponsorItem/'.$request->src; 
      if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete('/sponsorItem/'.$request->src);  
        return response()->json(['message' => 'Gambar berhasil dihapus'], 200);
      } else {
        return response()->json(['message' => 'Gambar tidak ditemukan'], 404);
      }       

    }


public function loadImages()
{
    $images = [];
    $files = Storage::files('public/sponsorItem');

    foreach ($files as $file) {
        $fileName = basename($file);
        $fileUrl = asset('storage/sponsorItem/' . $fileName);        
        $images[] = [
            'url' => $fileUrl,
            'thumb' => $fileUrl,
            'id' => $fileName,
            "tag" => explode('.', $fileName)[0],
            "name" => explode('.', $fileName)[0]
        ];
    }

    return response()->json($images);
}

}
