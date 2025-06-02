<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pengeluaran;
use App\rekapitulasi;
use App\pemasukan;
use App\kategori;
use App\User;
use App\kas;
use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Role;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
class pengeluaranController extends Controller
{
   
    public function index(Request $request)
    {
 
        $kataKunci = request()->q;
        $urutan = request()->urutan;   
        $tahun = request()->tahun;
        $bulan = request()->bulan; 
        $rekapitulasi = Rekapitulasi::with(['pengeluaran.kategori', 'pengeluaran.user.Role'])
            ->orderBy('created_at', $urutan);
            
        if($bulan) {
            $rekapitulasi->whereMonth('created_at', $bulan);
        }       
        if($tahun) {
            $rekapitulasi->whereYear('created_at', $tahun);
        }        
        // Filter berdasarkan uraian, total, dan kategori
        $rekapitulasi = $rekapitulasi->whereHas('pengeluaran', function ($query) use ($kataKunci) {
            $query->where('uraian', 'like', '%' . $kataKunci . '%')
                ->orWhere('total', 'like', '%' . $kataKunci . '%')
                ->orWhereHas('kategori', function ($query) use ($kataKunci) {
                    $query->where('nama', 'like', '%' . $kataKunci . '%');
                });
        });
        // if( request()->mulai_tgl and request()->akhir_tgl ){
        //     $rekapitulasi = $rekapitulasi->whereBetween('created_at', array( request()->mulai_tgl, request()->akhir_tgl) );
        // }    
        $rekapitulasi = $rekapitulasi->paginate(request()->per_page);    
        return new UserCollection($rekapitulasi);  
    }

    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/pengeluaran');
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
          'kategori_id' => 'required',
          'total' => 'required'
        ]);   
    try {     
        $rekapitulasi = rekapitulasi::latest()->first();  
        $foto = "";

        if($request->hasFile('foto')){             
            $foto =  $this->simpan_file( $request->file('foto') ); 
         }  

      
         //proses input kass masjid atau kas bank
         $input_kas = $request->kas;
         $kas = kas::latest()->first();
         if($input_kas == 'masjid') {
             $kas->masjid = $kas->masjid - $request->total;
             $kas->perubahanM = true;
             $kas->save();
         } else { //berarti bank
             $kas->bank =  $kas->bank - $request->total;
             $kas->perubahanB = true;
             $kas->save();
         }           


        $input = $request->all();   
        $input['rekapitulasi_id'] = $rekapitulasi->id;
        $input['foto'] = $foto ? $foto : 'default.png';
        $input['user_id'] = Auth::user()->id;
         pengeluaran::create($input);  

        $total_pengeluaran_rek = pengeluaran::where('rekapitulasi_id', $rekapitulasi->id)->sum('total');
        $pengeluaran_input = $request->total;

        if($rekapitulasi) {                 
            $saldo_akhir = $rekapitulasi->saldo_akhir - $pengeluaran_input;
            //saldo akhir
            $rekapitulasi->saldo_akhir =  $saldo_akhir;     
            //pengeluaran_rek
            $rekapitulasi->pengeluaran_rek = $total_pengeluaran_rek;            

            $rekapitulasi->save();
        }   
        return response()->json(['status' => 'success' , "pesan" => $request->file('foto')], 200);
        
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
    }
  

    public function edit($id)
    {
        $data = pengeluaran::with(['kategori', 'user'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = pengeluaran::with(['kategori', 'user'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [       
            'kategori_id' => 'required',
            'total' => 'required'
          ]);            
          try {    
              $data = pengeluaran::find($id); 
              $foto = $data->foto;   
                        
            if($request->hasFile('foto')){  
                !empty($foto) ? Storage::disk('public')->delete('/pengeluaran/'. $data->foto) : null;
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }         
       
              $input = $request->all(); 
              $input['foto'] = $foto;    
              
              
              //proses input kass masjid atau kas bank
              $input_kas = $request->kas;
              $kas = kas::latest()->first();
              if($input_kas == 'masjid' && $data->kas == 'masjid' ) {
                  //total dikembalikan dulu
                  $kas_masjid_awal = $kas->masjid + $data->total;
                  $kas->masjid = $kas_masjid_awal - $request->total;                  
                  $kas->save();
              } 
              
              //kondisi jika skrang kas bank dan sebelumnya kas masjid
              if($input_kas == 'bank' && $data->kas == 'masjid' ) {
                  //total dikembalikan dulu
                  $kas_masjid_awal = $kas->masjid + $data->total;
                  $kas->masjid = $kas_masjid_awal;
                  
                  $kas->bank = $kas->bank - $request->total;
                  $kas->save();
              } 

              if(($input_kas == 'bank' && $data->kas == 'bank')) { // bank                    
                  //total dikembalikan dulu
                  $kas_bank_awal = $kas->bank + $data->total;
                  $kas->bank = $kas_bank_awal - $request->total;
                  $kas->save();
              }

              //kondisi jika sekarang masjid dan sebelumnya bank                
              if(($input_kas == 'masjid' && $data->kas == 'bank')) { // bank                    
                  //total dikembalikan dulu
                  $kas_bank_awal = $kas->bank + $data->total;
                  $kas->bank = $kas_bank_awal;

                  $kas->masjid = $kas->masjid - $request->total;
                  $kas->save();
              }           

              $rekapitulasi = rekapitulasi::findOrFail($data->rekapitulasi_id); 
          
             $pengeluaran_input = $request->total;
            
                if($rekapitulasi) {                   
                    
                    //saldo sebelum edit
                        $saldo_akhir =  $rekapitulasi->saldo_akhir+$data->total;  
                        //  simpan pengeluaran      
                    $data->update($input);
                    //saldo final
                    $rekapitulasi->saldo_akhir = $saldo_akhir-$pengeluaran_input;
                    $total_pengeluaran_rek = pengeluaran::where('rekapitulasi_id', $data->rekapitulasi_id)->sum('total');
                       //pengeluaran_rek
                    $rekapitulasi->pengeluaran_rek = $total_pengeluaran_rek;            
        
                    $rekapitulasi->save();
                }              
               
          return response()->json(['status' => 'success'], 200);
          
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
    }

    public function destroy($id)
    {
        $data = pengeluaran::findOrFail($id); 
        $rekapitulasi = rekapitulasi::findOrFail($data->rekapitulasi_id); 
        Storage::disk('public')->delete('/pengeluaran/'. $data->foto);        
        
        //totalkan pengeluaran yang diedit kemudian update rekapan            
        $total_pengeluaran_rek = pengeluaran::where('rekapitulasi_id', $rekapitulasi->id)->sum('total');
       
        if($rekapitulasi) {         

            $rekapitulasi->pengeluaran_rek = $total_pengeluaran_rek-$data->total;
            //saldo akhir sebelum edit 
            $saldo_akhir = $rekapitulasi->saldo_akhir + $data->total;
            $rekapitulasi->saldo_akhir = $saldo_akhir;

            $rekapitulasi->save();
        }    
        $kas = kas::latest()->first();
        if($data->kas == 'masjid') {
            $kas->masjid =  $kas->masjid + $data->total;
            $kas->save();
        } else { //berarti bank
            $kas->bank = $kas->bank + $data->total;
            $kas->save();
        }



        $data->delete(); //hapus pengeluaran

        // $ubah_array =  explode(",",$id);
        // pengeluaran::whereIn('id', $ubah_array)->delete();

        return response()->json(['status' => 'success'], 200);
    }

    

}
