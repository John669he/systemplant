<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class provedoes extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCoEvent';
    protected $fillable = ['idCoEvent','fechaContra','status'];
}
