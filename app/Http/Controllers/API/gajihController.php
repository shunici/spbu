<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;
use App\gajih;
use App\jabatan;
use App\User;
use Carbon\Carbon;
class gajihController extends Controller
{
   
    public function index(Request $request)
    {
        $tahun = request()->tahun;
        $bulan = request()->bulan;
        $gajih = gajih::with(['user.jabatan'])->orderBy('created_at', 'DESC');   
        if($bulan) {
            $gajih->whereMonth('tgl', $bulan);
        }       
        if($tahun) {
            $gajih->whereYear('tgl', $tahun);
        }   
        $data = $gajih->get();
        return new UserCollection($data);
    }

    public function intensive (Request $request)
    {
        $id = request()->id;
        $tahun = request()->tahun;
        $bulan = request()->bulan;

        $gajih = gajih::with(['user.jabatan'])
        ->where('user_id', $id)
        ->orderBy('tgl', 'DESC');

        if($bulan) {
            $gajih->whereMonth('tgl', $bulan);
        }       
        if($tahun) {
            $gajih->whereYear('tgl', $tahun);
        }   
        $data = $gajih->get();   
       
        return response()->json(['data' => $data]);
    }

    public function data_user(Request $request)
    {
        
        // $date = Carbon::now(); 
        // $date->month;       
        $total_user = user::with(['jabatan'])->where('status', 1)->get();
//query yg bkan sueper admin dan juga bukan petugas, agar queri hanya orang orang masjid
          $data  = User::with(['jabatan'])->whereNotIn('role_id', [1, 5])->whereDoesntHave('gajih', function($query) {
            $query->whereMonth('created_at', Carbon::now()->month);                  
        })->get();
        
        return response()->json([
            'status' => 'success', 
            'data' => $data,
            'total_user' => $total_user
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'penerimaan' => 'required',    
          'user_id' => 'required',              
        ]);  
        try {                  
            $input = $request->all();                    
            gajih::create($input);     
            return response()->json(['status' => 'success'], 200);        
        
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }
  

    public function edit($id)
    {
        $data = gajih::with(['user.jabatan'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }   

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'penerimaan' => 'required',    
            'user_id' => 'required',               
          ]);   
        try {    
            $data = gajih::find($id);                                             
            $input = $request->all();   
            $input['ket_penerimaan']  = json_encode($request->ket_penerimaan);
            $input['ket_pengurangan']  = json_encode($request->ket_pengurangan);
            $data->update($input);               
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
    }

    public function destroy($id)
    {
        $data = gajih::findOrFail($id);        
        $data->delete();     
        return response()->json(['status' => 'success'], 200);
    }

    public function kirim_slip(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'message' => 'required|string',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        // Simpan file sementara di public/temp
    $file = $request->file('attachment');
    $filePath = $file->storeAs('temp', $file->getClientOriginalName(), 'public');

    // URL file yang akan dikirim
    $fileUrl = asset('storage/' . $filePath);

    $apiToken = 'fcwokXLmNHnJs7kGuCrB';
    $data = [
        'target' => '6283892696246', // Nomor tujuan (gunakan format internasional)
        'message' => $request->message, // Pesan yang dikirim
        'url' =>  'https://md.fonnte.com/new/assets/img/logo.png'
    ];
    // Kirim file ke API Fonnte
 // Kirim permintaan ke API Fonnte
 $response = Http::withHeaders([
    'Authorization' => $apiToken,
])->post('https://api.fonnte.com/send', $data);

    // Hapus file dari public/temp setelah berhasil dikirim
    if ($response->successful()) {
        Storage::disk('public')->delete($filePath);
        return response()->json(['message' => 'Pesan berhasil dikirim dan file dihapus.'], 200);
    }

    // Jika pengiriman gagal
    return response()->json(['error' => 'Pesan gagal dikirim.'], 500);
    }

}


