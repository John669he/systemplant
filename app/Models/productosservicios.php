<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productosservicios extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPS';
    protected $fillable = ['idPS','idPro','nombre','producto_servicio','precioBase'];
}
