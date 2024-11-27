<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detallesps extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCoEvent';
    protected $fillable = ['idPS','idCoEvent','cantidad','precioBase'];
}
