<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pengeluaran extends Model
{
    protected $guarded = [];
    
    public function rekapitulasi()
    {
        return $this->belongsTo(Rekapitulasi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(kategori::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
