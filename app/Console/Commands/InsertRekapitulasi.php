<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Console\Command;

class InsertRekapitulasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:rekapitulasi';

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
        $tgl_mulai = Carbon::now();
        $jumatDepan = Carbon::now()->next('Friday')->setTime(12, 0);
        $rek = DB::table('rekapitulasis')->latest()->first();
        $users = DB::table('rekapitulasis')->insert(
            [ 
                "keterangan" => "rekapitulasi ". Carbon::now(),
                "saldo_awal" => $rek->saldo_akhir,
                "saldo_akhir" => $rek->saldo_akhir,
                "pemasukan_rek" => 0,
                "pengeluaran_rek" => 0,
                "tgl_mulai" => $tgl_mulai,
                "tgl_akhir" => $jumatDepan,
                "created_at" => $tgl_mulai,
                "updated_at" => $tgl_mulai
                
            ]
            );
        $name = $this->info('insert rekap');
    }
}
