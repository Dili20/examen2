<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = 'listas';
    protected $primaryKey = 'id_lista';
    public $timestamps = false;
    protected $fillable = ['id_usuario', 'titulo', 'token'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'id_lista', 'id_lista');
    }
}