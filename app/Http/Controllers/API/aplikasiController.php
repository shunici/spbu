<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\aplikasi;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class aplikasiController extends Controller
{
    
    public function index()
    {
        $data = aplikasi::first();

        if ($data) {
          $data->increment('kunjungan'); // Field kunjungan akan bertambah 1
         }
         $data = $data->toArray(); // Konversi ke array agar bisa dimodifikasi
         $bantuan = [
         [
          "question" => "Mengapa website ini dibuat?",
          "answer" => "Website ini dibuat untuk mempermudah pengelolaan keuangan  agar lebih transparan dan akuntabel. Dengan adanya sistem ini, admin bisa dengan mudah melihat pemasukan, pengeluaran, serta laporan keuangan  secara real-time."
         ],
          [
              "question" => "Bagaimana cara melihat laporan keuangan?",
              "answer" => "Anda dapat melihat laporan keuangan  dengan masuk ke menu 'Rekapitulasi' dan memilih periode laporan yang ingin dilihat."
          ],
          [
              "question" => "Siapa yang bisa mengakses laporan keuangan?",
              "answer" => "Laporan keuangan bisa diakses oleh pegawai dan yang diberikan izin oleh admin."
          ],
          [
              "question" => "Bagaimana cara mencatat pemasukan dan pengeluaran?",
              "answer" => "pegawai  dapat mencatat pemasukan dan pengeluaran di menu 'Keuangan' dengan mengisi form yang telah disediakan."
          ],
          [
              "question" => "Bagaimana jika ada kesalahan pencatatan transaksi?",
              "answer" => "Jika terjadi kesalahan pencatatan, segera hubungi admin  untuk dilakukan perbaikan data."
          ],
          [
              "question" => "Apakah website ini aman untuk menyimpan data keuangan?",
              "answer" => "Ya, website ini menggunakan sistem keamanan yang terenkripsi dan hanya bisa diakses oleh pihak yang berwenang."
          ],
          [
              "question" => "Bagaimana jika saya ingin mengajukan pertanyaan lain?",
              "answer" => "Dibagian paling bawah ada Kontak untuk menghubungi pengurus  atau kirim pesan melalui WhatsApp yang tersedia."
          ],

      ];

    if ($data) {
        $data['bantuan'] = $bantuan;
    }


        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'logo' => 'max:2048',  
          ]);   
          $data = aplikasi::find($id);
          
          $logo = $data->logo;          
          if($request->hasFile('logo')){  
            !empty($logo) ? Storage::disk('public')->delete('/aplikasi/'. $data->logo) : null;
            $logo =  $this->simpan( $request->file('logo') ); 
          }      

          $kop = $data->kop;          
          if($request->hasFile('kop')){  
            !empty($kop) ? Storage::disk('public')->delete('/aplikasi/'. $data->kop) : null;
            $kop =  $this->simpan( $request->file('kop') ); 
          } 
          
             
          $foto1 = $data->foto1;       //backround    
          if($request->hasFile('foto1')){  
            !empty($foto1) ? Storage::disk('public')->delete('/aplikasi/'. $data->foto1) : null;
            $foto1 =  $this->simpan( $request->file('foto1') ); 
          }  

             
          $foto2 = $data->foto2;          
          if($request->hasFile('foto2')){  
            !empty($foto2) ? Storage::disk('public')->delete('/aplikasi/'. $data->foto2) : null;
            $foto2 =  $this->simpan( $request->file('foto2') ); 
          }  

             
          $foto3 = $data->foto3;          
          if($request->hasFile('foto3')){  
            !empty($foto3) ? Storage::disk('public')->delete('/aplikasi/'. $data->foto3) : null;
            $foto3 =  $this->simpan( $request->file('foto3') ); 
          }  

             
          $foto4 = $data->foto4;          
          if($request->hasFile('foto4')){  
            !empty($foto4) ? Storage::disk('public')->delete('/aplikasi/'. $data->foto4) : null;
            $foto4 =  $this->simpan( $request->file('foto4') ); 
          }  
                                       
              $input = $request->all();   
              $input['logo'] = $logo;  
              $input['kop'] = $kop; 
              $input['foto1'] = $foto1; 
              $input['foto2'] = $foto2; 
              $input['foto3'] = $foto3; 
              $input['foto4'] = $foto4;          
              $data->update($input);
               
          return response()->json(['status' => 'sukses']);
    }

    private function simpan($file)
    {
        $ori_name = $file->getClientOriginalName();
        $fileName =  $ori_name. time() .'.'. $file->getClientOriginalExtension();
        $file->storeAs('public/aplikasi', $fileName);
        return $fileName;
    }

  

    
    public function upload(Request $request)
    {
      if ($request->hasFile('file')) {
        $image = $request->file('file');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/upload', $imageName);

        $imageUrl = asset('/storage/upload/' . $imageName);
        return response()->json(['link' => $imageUrl]);
    }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function deleteImage(Request $request)
    {
  
      $filePath = 'upload/'.$request->src; 
      if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete('/upload/'.$request->src);  
        return response()->json(['message' => 'Gambar berhasil dihapus'], 200);
      } else {
        return response()->json(['message' => 'Gambar tidak ditemukan'], 404);
      }       

    }


public function loadImages()
{
    $images = [];
    $files = Storage::files('public/masjid');

    foreach ($files as $file) {
        $fileName = basename($file);
        $fileUrl = asset('storage/masjid/' . $fileName);        
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
