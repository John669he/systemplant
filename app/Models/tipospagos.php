<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipospagos extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPagos';
    protected $fillable = ['idPagos','nombre'];
}
