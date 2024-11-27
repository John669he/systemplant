<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCatEvent';
    protected $fillable = ['idCatEvent','nombre'];
}
