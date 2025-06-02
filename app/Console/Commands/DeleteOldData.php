<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class DeleteOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sebuah penghapusan';

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

        $rek = DB::table('rekapitulasis')->latest()->first();

        $users = DB::table('rekapitulasis')->insert(
            [ 
                "keterangan" => "rekapitulasi",
                "saldo_awal" => $rek->saldo_akhir,
                "saldo_akhir" => $rek->saldo_akhir,
                "pemasukan_rek" => 0,
                "pengeluaran_rek" => 0,
                
            ]
            );
        // $name = $this->info();
        


    }
}
