<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
class User extends Authenticatable

{
    use Notifiable, HasApiTokens, HasRoles;
    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'foto', 'hp', 'jabatan_id', 'alamat', 'status', 'nomor_urut'
    ];
    public function Role()
    {
        return $this->belongsTo(Role::class);
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // public function getNameAttribute($value)
    // {
    //     return ucfirst($value);
    // }
    
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

    public function jabatan()
    {
        return $this->belongsTo(jabatan::class);
    }

    public function gajih()
    {
        return $this->hasOne(gajih::class, 'user_id');
    }

    public function sholatImam()
    {
        return $this->hasMany(sholat::class, 'imam');
    }

    public function sholatImamPengganti()
    {
        return $this->hasMany(sholat::class, 'imam_pengganti');
    }

    
    public function sholatMuadzin()
    {
        return $this->hasMany(sholat::class, 'muadzin');
    }

    public function sholatMuadzinPengganti()
    {
        return $this->hasMany(sholat::class, 'muadzin_pengganti');
    }
}
