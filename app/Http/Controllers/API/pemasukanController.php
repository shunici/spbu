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
use PDF;
use Carbon\Carbon;
use App\aplikasi;

class pemasukanController extends Controller
{
  
    public function pemasukantabel(Request $request)
{

    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');

    // Ambil semua data pemasukan sesuai filter
    $pemasukanData = pemasukan::with(['kategori', 'user.role', 'user.jabatan'])
        ->when($bulan, function ($query) use ($bulan) {
            return $query->whereMonth('tgl', $bulan);
        })
        ->when($tahun, function ($query) use ($tahun) {
            return $query->whereYear('tgl', $tahun);
        })
        ->orderBy('tgl', 'asc')
        ->get();

    // Hitung total per kategori
    $kategoriTotals = [];
    foreach ($pemasukanData as $item) {
        $namaKategori = $item->kategori->nama ?? 'Lainnya';
        if (!isset($kategoriTotals[$namaKategori])) {
            $kategoriTotals[$namaKategori] = 0;
        }
        $kategoriTotals[$namaKategori] += $item->total;
    }

    // Ambil hanya kategori yang totalnya lebih dari 0
    $kategoriFiltered = collect($kategoriTotals)
        ->filter(function ($total) {
            return $total > 0;
        })
        ->keys()
        ->values();

    // Bentuk data baris per baris sesuai kategori yang dipilih
    $data = $pemasukanData->map(function ($item, $index) use ($kategoriFiltered) {
        $row = [
            'no' => $index + 1,
            'uraian' => $item->uraian,
            'foto' => $item->foto,
            'foto1' => $item->foto1,
            'foto2' => $item->foto2,
            'foto3' => $item->foto3,
            'tgl' => $item->tgl,
        ];

        foreach ($kategoriFiltered as $kategori) {
            $row[$kategori] = ($item->kategori->nama === $kategori)
                ? $item->total
                : 0;
        }

        return $row;
    });

    // Hitung total keseluruhan semua pemasukan
    $totalKeseluruhan = $pemasukanData->sum('total');

    // Return dalam format JSON
    return response()->json([
        'status' => 'success',
        'kategori_header' => $kategoriFiltered,
        'data' => $data,
        'total' => $totalKeseluruhan,
    ]);
}

    
  public function index(Request $request)
  {
      $namaKategori = $request->input('kategori');
    $bulan        = $request->input('bulan');
    $tahun        = $request->input('tahun');
    
    // Query dasar
    $query = pemasukan::with(['kategori', 'user.role', 'user.jabatan'])
        ->when($namaKategori, function ($query) use ($namaKategori) {
            $query->whereHas('kategori', function ($q) use ($namaKategori) {
                $q->where('id', $namaKategori);
            });
        })
        ->when($bulan, function ($query) use ($bulan) {
            $query->whereMonth('tgl', $bulan);
        })
        ->when($tahun, function ($query) use ($tahun) {
            $query->whereYear('tgl', $tahun);
        });
    
    // Ambil total dan data secara terpisah
    $total = $query->sum('total');
    $data  = $query->orderBy('tgl', 'asc')->get();   

    return response()->json(['status' => 'success' , "data" => $data, "total" => $total], 200);
  }

    public function insdex(Request $request)
    {
 
        $kataKunci = request()->q;
        $urutan = request()->urutan;   
        $tahun = request()->tahun;
        $bulan = request()->bulan; 
        
        $rekapitulasi = Rekapitulasi::with([
            'pemasukan' => function ($query) use ($urutan) {
                $query->orderBy('tgl', 'asc') // atau ganti dengan 'tgl' jika ada
                      ->with(['kategori', 'user.Role', 'user.jabatan']);
            }
        ])->orderBy('created_at', $urutan);
        
        if ($bulan) {
            $rekapitulasi->whereMonth('created_at', $bulan);
        }       
        
        if ($tahun) {
            $rekapitulasi->whereYear('created_at', $tahun);
        }        
        
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

    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/pemasukan');
         if(!File::isDirectory($path)) {
             File::makeDirectory($path, 0777, true, true);
         }
         $width = 900; // your max width
         $height = 750; // your max height

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
        $foto1 = "";
        $foto2 = "";
        $foto3 = "";
        if($request->hasFile('foto')){             
            $foto =  $this->simpan_file( $request->file('foto') ); 
         }  
    
         if($request->hasFile('foto1')){             
            $foto1 =  $this->simpan_file( $request->file('foto1') ); 
         }  
         
         if($request->hasFile('foto2')){             
            $foto2 =  $this->simpan_file( $request->file('foto2') ); 
         }  
                  
        if($request->hasFile('foto3')){             
            $foto3 =  $this->simpan_file( $request->file('foto3') ); 
         }  
         //proses input kass kantor atau kas bank
         $input_kas = $request->kas;
        $kas = kas::latest()->first();
        if($input_kas == 'kantor') {
            $kas->kantor =  $kas->kantor + $request->total;
            $kas->perubahanM = true;
            $kas->save();
        } else { //berarti bank
            $kas->bank = $kas->bank + $request->total;
            $kas->perubahanB = true;
            $kas->save();
        }

      
        $input = $request->all();   
        $input['rekapitulasi_id'] = $rekapitulasi->id;
        $input['foto'] = $foto ? $foto : 'default.png';
        $input['foto1'] = $foto1 ? $foto1 : 'default.png';
        $input['foto2'] = $foto2 ? $foto2 : 'default.png';
        $input['foto3'] = $foto3 ? $foto3 : 'default.png';
        $input['user_id'] = Auth::user()->id;
         pemasukan::create($input);  

         $pengeluaran_input = $request->total;
        $total_pemasukan = pemasukan::where('rekapitulasi_id', $rekapitulasi->id)->sum('total');

        if($rekapitulasi) {
             

            $rekapitulasi->pemasukan_rek = $total_pemasukan;
            //saldo akhir sbelum edit 
            $saldo_akhir = $rekapitulasi->saldo_akhir;
            //saldo akhir final
            $rekapitulasi->saldo_akhir = $saldo_akhir +  $pengeluaran_input;

            $rekapitulasi->save();
        }   
             return response()->json(['status' => 'success' , "pesan" => $request->file('foto')], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
    }
  

    public function edit($id)
    {
        $data = pemasukan::with(['kategori', 'user'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = pemasukan::with(['kategori', 'user'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [            
            'kategori_id' => 'required',
            'total' => 'required'
          ]);            
    try {       
              $data = pemasukan::find($id); 
              $foto = $data->foto;   
              $foto1 = $data->foto1; 
              $foto2 = $data->foto2; 
              $foto3 = $data->foto3;  
                        
            if($request->hasFile('foto')){  
                !empty($foto) ? Storage::disk('public')->delete('/pemasukan/'. $data->foto) : null;
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }                  
            
                  
            if($request->hasFile('foto1')){  
                !empty($foto1) ? Storage::disk('public')->delete('/pemasukan/'. $data->foto1) : null;
                $foto1 =  $this->simpan_file( $request->file('foto1') ); 
            }  
                        
            if($request->hasFile('foto2')){  
                !empty($foto2) ? Storage::disk('public')->delete('/pemasukan/'. $data->foto2) : null;
                $foto2 =  $this->simpan_file( $request->file('foto2') ); 
            }  

                        
            if($request->hasFile('foto3')){  
                !empty($foto3) ? Storage::disk('public')->delete('/pemasukan/'. $data->foto3) : null;
                $foto3 =  $this->simpan_file( $request->file('foto3') ); 
            } 

              $input = $request->all(); 
              $input['foto'] = $foto;              
              $input['foto1'] = $foto1; 
              $input['foto2'] = $foto2; 
              $input['foto3'] = $foto3;  
                //proses input kass kantor atau kas bank
                $input_kas = $request->kas;
                $kas = kas::latest()->first();
                if($input_kas == 'kantor' && $data->kas == 'kantor' ) {
                    //total dikembalikan dulu
                    $kas_kantor_awal = $kas->kantor - $data->total;
                    $kas->kantor = $kas_kantor_awal + $request->total;
                    $kas->save();
                } 
                
                //kondisi jika skrang kas bank dan sebelumnya kas kantor
                if($input_kas == 'bank' && $data->kas == 'kantor' ) {
                    //total dikembalikan dulu
                    $kas_kantor_awal = $kas->kantor - $data->total;
                    $kas->kantor = $kas_kantor_awal;
                    
                    $kas->bank = $kas->bank + $request->total;
                    $kas->save();
                } 

                if(($input_kas == 'bank' && $data->kas == 'bank')) { // bank                    
                    //total dikembalikan dulu
                    $kas_bank_awal = $kas->bank - $data->total;
                    $kas->bank = $kas_bank_awal + $request->total;
                    $kas->save();
                }

                //kondisi jika sekarang kantor dan sebelumnya bank                
                if(($input_kas == 'kantor' && $data->kas == 'bank')) { // bank                    
                    //total dikembalikan dulu
                    $kas_bank_awal = $kas->bank - $data->total;
                    $kas->bank = $kas_bank_awal;

                    $kas->kantor = $kas->kantor + $request->total;
                    $kas->save();
                }

             

              $rekapitulasi = rekapitulasi::findOrFail($data->rekapitulasi_id); 
              $pemasukan_input = $request->total;
            
                if($rekapitulasi) {
                 //saldo sebelum edit
                        $saldo_akhir =  $rekapitulasi->saldo_akhir-$data->total;  
                        //  simpan pemsukan      
                        $data->update($input);
                        //saldo final
                        $rekapitulasi->saldo_akhir = $saldo_akhir+$pemasukan_input;
                        $total_pemasukan_rek= pemasukan::where('rekapitulasi_id', $data->rekapitulasi_id)->sum('total');
                            //pemsukan_rek
                        $rekapitulasi->pemasukan_rek= $total_pemasukan_rek;            
            
                        $rekapitulasi->save();
                }                  
          return response()->json(['status' => 'success', 'pesan' =>  $input_kas], 200);
          
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }   
    }

    public function destroy($id)
    {
        $data = pemasukan::findOrFail($id); 
        $rekapitulasi = rekapitulasi::findOrFail($data->rekapitulasi_id); 
        Storage::disk('public')->delete('/pemasukan/'. $data->foto);        
      
        //totalkan pemasukan yang diedit kemudian update rekapan            
        $total_pemasukan = pemasukan::where('rekapitulasi_id', $rekapitulasi->id)->sum('total');
       
        if($rekapitulasi) {        
            $rekapitulasi->pemasukan_rek = $total_pemasukan-$data->total;
            //saldo akhir sebelum edit 
            $saldo_akhir = $rekapitulasi->saldo_akhir - $data->total;
            $rekapitulasi->saldo_akhir = $saldo_akhir;

            $rekapitulasi->save();
        }    

        $kas = kas::latest()->first();
        
        if($data->kas == 'kantor') {
            $kas->kantor =  $kas->kantor - $data->total;
            $kas->save();
        } else { //berarti bank
            $kas->bank = $kas->bank - $data->total;
            $kas->save();
        }


    $data->delete();  

        // $ubah_array =  explode(",",$id);
        // pemasukan::whereIn('id', $ubah_array)->delete();

        return response()->json(['status' => 'success'], 200);
    }




    
    public function cetakPDF(Request $request)
    {

        $aplikasi = aplikasi::latest()->first();
        Carbon::setLocale('id');
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');

    $pemasukanData = pemasukan::with(['kategori', 'user.role', 'user.jabatan'])
        ->when($bulan, function ($query) use ($bulan) {
            return $query->whereMonth('tgl', $bulan);
        })
        ->when($tahun, function ($query) use ($tahun) {
            return $query->whereYear('tgl', $tahun);
        })
        ->orderBy('tgl', 'asc')
        ->get();

    $kategoriTotals = [];
    foreach ($pemasukanData as $item) {
        $kategori = $item->kategori->nama ?? 'Lainnya';
        if (!isset($kategoriTotals[$kategori])) {
            $kategoriTotals[$kategori] = 0;
        }
        $kategoriTotals[$kategori] += $item->total;
    }

    $kategoriFiltered = collect($kategoriTotals)->filter(function ($total) {
        return $total > 0;
    })->keys()->values();

    $data = $pemasukanData->map(function ($item, $index) use ($kategoriFiltered) {
        $uraian = preg_replace('/<p[^>]*>.*?Powered by.*?<\/p>/is', '', $item->uraian); //hilangkan froala
        $uraian = preg_replace('/\s*style="[^"]*"/i', '', $uraian); // hilangkan syle

        $row = [
            'no' => $index + 1,
            'uraian' => $uraian,
            'tgl' =>  Carbon::parse($item->tgl)->format('j-n'),
        ];

        foreach ($kategoriFiltered as $kategori) {
            $row[$kategori] = ($item->kategori->nama === $kategori) ? $item->total : 0;
        }

        return $row;
    });

    $totalKeseluruhan = $pemasukanData->sum('total');

    // Total per kategori disiapkan kembali untuk footer
    $kategoriTotalFinal = [];
    foreach ($kategoriFiltered as $kategori) {
        $kategoriTotalFinal[$kategori] = isset($kategoriTotals[$kategori]) ? $kategoriTotals[$kategori] : 0;
    }

    $pdf = PDF::loadView('pdf.laporan_pemasukan', [
        'kategori_header' => $kategoriFiltered,
        'data' => $data,
        'kategori_total' => $kategoriTotalFinal,
        'total' => $totalKeseluruhan,
        'total_terbilang' => $this->terbilang($totalKeseluruhan) . ' Rupiah',
        'bulan_saja' => Carbon::create()->month($bulan)->translatedFormat('F'),
        'tahun' => $tahun,
        'aplikasi_nama' => $aplikasi->nama,
        'logo' => public_path('storage/aplikasi/' . $aplikasi->logo),
        "title" => 'Laporan Pemasukan',
        "url" => 'https://spbu.site/',
        "description" => 'Laporan Pemasukan '. Carbon::create()->month($bulan)->translatedFormat('F').' '.$tahun,

        
    ]);
    $pdf->setPaper('A4', 'landscape');
    return $pdf->stream('laporan_pemasukan.pdf');
    }







private function terbilang($angka)
{
    $angka = abs($angka);
    $bilangan = [
        "", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"
    ];

    if ($angka < 12) {
        return $bilangan[$angka];
    } elseif ($angka < 20) {
        return $bilangan[$angka - 10] . ' belas';
    } elseif ($angka < 100) {
        return $this->terbilang(floor($angka / 10)) . ' puluh ' . $this->terbilang($angka % 10);
    } elseif ($angka < 200) {
        return 'seratus ' . $this->terbilang($angka - 100);
    } elseif ($angka < 1000) {
        return $this->terbilang(floor($angka / 100)) . ' ratus ' . $this->terbilang($angka % 100);
    } elseif ($angka < 2000) {
        return 'seribu ' . $this->terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        return $this->terbilang(floor($angka / 1000)) . ' ribu ' . $this->terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        return $this->terbilang(floor($angka / 1000000)) . ' juta ' . $this->terbilang($angka % 1000000);
    }

    return 'angka terlalu besar';
}

    
}