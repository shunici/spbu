<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $guarded = [];
    
    public function penyaluran()
    {
        return $this->hasMany(penyaluran::class);
    }

    public function penerimaan()
    {
        return $this->hasMany(penerimaan::class);
    }
}
