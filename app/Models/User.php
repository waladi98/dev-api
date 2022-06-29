<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = 'kodec_sys_pengguna';
    protected $primaryKey = 'kode';
    protected $keyType    = 'string';
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'kode',
        'nama',
        'email',
        'kata_sandi',
        'pin',
        'kelompok',
        'akses_terakhir'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password','pin',
    ];
}
