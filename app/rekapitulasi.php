<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rekapitulasi extends Model
{
    protected $guarded = [];
    public function pengeluaran()
    {
        return $this->hasMany(pengeluaran::class);
    }

    public function pemasukan()
    {
        return $this->hasMany(pemasukan::class);
    }
}
