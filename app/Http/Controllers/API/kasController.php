<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserCollection;
use App\User;
use App\kas;
use Spatie\Permission\Models\Role;
use App\jabatan;

class kasController extends Controller
{
  public function index (Request $request)
  {
    $tahun = request()->tahun;
    $bulan = request()->bulan;
    $kas = kas::with(['user.Role', 'user.jabatan'])->orderBy('created_at', 'ASC');
    if($bulan) {
        $kas->whereMonth('created_at', $bulan);
    }       
    if($tahun) {
        $kas->whereYear('created_at', $tahun);
    }
    $kas = $kas->get();
    return new UserCollection($kas);
  }

  public function kas_sekarang (Request $request)
  {
    $kas = kas::latest()->first();   
    return response()->json(['status' => 'success', 'data' => $kas], 200);
  }

  
  public function store(Request $request)
  {
      $this->validate($request, [
        'input' => 'required',         
        'masjid' => 'required',    
        'bank' => 'required',      
      ]);      
      try {       
      $input = $request->all(); 
      $input['user_id'] = Auth::id();
      kas::create($input);     
      return response()->json(['status' => 'success'], 200);      
    } catch (Exception $e) {
      return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
     } 

  }

  public function destroy($id)
  {
      $data = kas::findOrFail($id);        
      $data->delete();   
      return response()->json(['status' => 'success'], 200);
  }


}
