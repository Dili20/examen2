<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id_item';
    public $timestamps = false;
    protected $fillable = ['id_lista', 'descripcion', 'completado'];

    public function lista()
    {
        return $this->belongsTo(Lista::class, 'id_lista', 'id_lista');
    }
}