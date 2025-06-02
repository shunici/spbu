<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pengeluaran;
use App\rekapitulasi;
use App\pemasukan;
use App\kategori;
use App\User;
use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Role;
use File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
class rekapitulasiController extends Controller
{
   
    public function index(Request $request)
    {
 
        $kataKunci = request()->q;
        $urutan = request()->urutan;             
        $tahun = request()->tahun;
        $bulan = request()->bulan;  
        $rekapitulasi = rekapitulasi::with(['pemasukan.kategori', 'pengeluaran.kategori', 'pemasukan.User.Role', 'pengeluaran.User.Role'])
            ->orderBy('created_at', $urutan);                  
        if($bulan) {
            $rekapitulasi->whereMonth('created_at', $bulan);
        }       
        if($tahun) {
            $rekapitulasi->whereYear('created_at', $tahun);
        }                   
    
    // Filter berdasarkan uraian, total, dan kategori
        $rekapitulasi = $rekapitulasi->whereHas('pemasukan', function ($query) use ($kataKunci) {
            $query->where('uraian', 'like', '%' . $kataKunci . '%')
                ->orWhere('total', 'like', '%' . $kataKunci . '%')
                ->orWhereHas('kategori', function ($query) use ($kataKunci) {
                    $query->where('nama', 'like', '%' . $kataKunci . '%');
                });
        });      
    
        $rekapitulasi = $rekapitulasi->paginate(request()->per_page);    
        return new UserCollection($rekapitulasi); 
    } 

    public function rekapitulasi_sekarang(Request $request)
    {
 
       
        $data = rekapitulasi::with(['pemasukan', 'pengeluaran'])->latest()->first();                             
        return response()->json(['status' => 'success', 'data' => $data], 200);
    } 


    public function show($id)
    {
        $data = rekapitulasi::with(['pemasukan.kategori', 'pengeluaran.kategori',])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function terbaru()
    {
        $data = rekapitulasi::latest()->first();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    
    public function rekapitulasi_grafis(Request $request)
    {        

        $tahun = request()->tahun;
        $bulan = request()->bulan;
        $rekapitulasi = rekapitulasi::query();
        
        // Filter berdasarkan tahun
        if ($tahun) {
            $rekapitulasi->whereYear('created_at', $tahun);
        }
        
        if(!$bulan) {            
                    // Menghitung total pemasukan dan pengeluaran per bulan
                    $data = $rekapitulasi
                    ->selectRaw('MONTH(created_at) as bulan, SUM(pemasukan_rek) as total_pemasukan, SUM(pengeluaran_rek) as total_pengeluaran')
                    ->groupByRaw('MONTH(created_at)')
                    ->orderByRaw('MONTH(created_at)')
                    ->get();
                
                // Inisialisasi array dengan 12 bulan (diisi 0 jika tidak ada datanya)
                $array_pemasukan = array_fill(0, 12, 0);
                $array_pengeluaran = array_fill(0, 12, 0);
                
                // Isi array pemasukan dan pengeluaran berdasarkan data bulan
                foreach ($data as $item) {
                    $indexBulan = $item->bulan - 1; // Index 0 untuk Januari, 1 untuk Februari, dst.
                    $array_pemasukan[$indexBulan] = $item->total_pemasukan;
                    $array_pengeluaran[$indexBulan] = $item->total_pengeluaran;
                }
                
                $data_rek = [
                    'pengeluaran' =>$array_pengeluaran ,
                    'pemasukan' => $array_pemasukan ,
                ];
        } else {                        
            $data_rek = [
                'pengeluaran' => [$rekapitulasi->whereMonth('created_at', $bulan)->sum('pengeluaran_rek')],
                'pemasukan' => [$rekapitulasi->whereMonth('created_at', $bulan)->sum('pemasukan_rek')],
            ];
        }

        // Output array pemasukan dan pengeluaran
        return response()->json([
            'data' => $data_rek,            
        ]);
    } 

    public function rekapitulasi_grafis_saldo(Request $request)
    {        
//mengambil data terakhir saldo akhir disetiap bulan atau satu bulan aja
        $tahun = request()->tahun;
        $bulan = request()->bulan;
        $rekapitulasi = rekapitulasi::query();
        
        // Filter berdasarkan tahun
        if ($tahun) {
            $rekapitulasi->whereYear('created_at', $tahun);
        }
        
        if (!$bulan) {
            // Ambil data terakhir per bulan
            $data = $rekapitulasi
                ->select('id', 'created_at', 'saldo_akhir')
                ->whereIn('id', function ($query) use ($tahun) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('rekapitulasis')
                        ->when($tahun, function ($query) use ($tahun) {
                            $query->whereYear('created_at', $tahun);
                        })
                        ->groupBy(DB::raw('MONTH(created_at)'));
                })
                ->orderByRaw('MONTH(created_at)')
                ->get();
        
            $array_saldo = array_fill(0, 12, 0);
        
            foreach ($data as $item) {
                $indexBulan = date('n', strtotime($item->created_at)) - 1; // Mengambil bulan dari 'created_at'
                $array_saldo[$indexBulan] = $item->saldo_akhir;
            }
        
            $data_rek = $array_saldo;
        } else {
            // Ambil data terbaru di bulan yang diminta
            $data_rek = [$rekapitulasi
                ->whereMonth('created_at', $bulan)
                ->latest()
                ->first()
                ->saldo_akhir ?? 0]; // Jika tidak ada data, kembalikan 0
        }
        
        return response()->json([
            'data' => $data_rek,
        ]);
        
    } 



    public function rekap_kategori(Request $request)
    { 
         $tahun = $request->tahun;
        $bulan = $request->bulan;
    
        // Filter pemasukan berdasarkan tahun dan bulan
        $pemasukan = pemasukan::with('kategori')
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('created_at', $tahun); // Sesuaikan 'created_at' dengan kolom tanggal Anda
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereMonth('created_at', $bulan); // Sesuaikan 'created_at' dengan kolom tanggal Anda
            })
            ->get();
    
        // Mengelompokkan dan menjumlahkan pemasukan
        $pemasukanByKategori = $pemasukan->groupBy('kategori.nama')->map(function ($items) {
            return $items->sum('total');
        });
    
        // Filter pengeluaran berdasarkan tahun dan bulan
        $pengeluaran = pengeluaran::with('kategori')
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('created_at', $tahun); // Sesuaikan 'created_at' dengan kolom tanggal Anda
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereMonth('created_at', $bulan); // Sesuaikan 'created_at' dengan kolom tanggal Anda
            })
            ->get();
    
        // Mengelompokkan dan menjumlahkan pengeluaran
        $pengeluaranByKategori = $pengeluaran->groupBy('kategori.nama')->map(function ($items) {
            return $items->sum('total');
        });
    
        // Menyusun data akhir
        $data = [
            'pemasukan' => $pemasukanByKategori,
            'pengeluaran' => $pengeluaranByKategori,
        ];
    
        return response()->json([
            'data' => $data,
        ]);

    }


}