<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detallespagoss extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCoEvent';
    protected $fillable = ['idPagos','idCoEvent','idTipoPago','fecha','monto'];
}
