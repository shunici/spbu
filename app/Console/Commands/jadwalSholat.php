<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\sholat;
use App\user;
use Illuminate\Support\Facades\Http;


class jadwalSholat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:sholat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
            // Mendapatkan nama hari hari ini
            $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');  // Misalnya: "Senin", "Selasa", dst.

            // Ambil jadwal sholat hari ini
            $jadwals = sholat::with(['imam', 'muadzin', 'imamPengganti', 'muadzinPengganti'])->where('hari', ucfirst($hariIni))->get();

            // Kirim pesan WhatsApp ke petugas yang sesuai
            $message = [];
            foreach ($jadwals as $jadwal) {
                $message = "Assalamu'alaikum {$jadwal->imam}, ini adalah pengingat bahwa Anda bertugas sebagai imam untuk sholat {$jadwal->nama} hari ini ({$jadwal->hari}).";     
                dd($jadwal->imam); 
                // $this->sendWhatsAppMessage($jadwal->imam->hp, $message);
            }
          
    }

    private function sendWhatsAppMessage($number, $message)
    {
        $apiToken = 'fcwokXLmNHnJs7kGuCrB';       

        $data = [
            'target' => $number,
            'message' => $message,
        ];

        $response = Http::withHeaders([
            'Authorization' => $apiToken,
        ])->post('https://api.fonnte.com/send', $data);    

        return $response->status();
    }
}
