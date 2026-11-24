<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $fillable = ['nombre', 'email', 'google_id'];

    public function listas()
    {
        return $this->hasMany(Lista::class, 'id_usuario', 'id_usuario');
    }
}