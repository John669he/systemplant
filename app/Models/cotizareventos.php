<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cotizareventos extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCoEvent';
    protected $fillable = ['idCoEvent','idCli','idCatEvent','fechaCoti','costo','fechaEvent'];
}
