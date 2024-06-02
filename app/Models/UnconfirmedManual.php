<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnconfirmedManual extends Model
{
    use HasFactory;

    protected $primaryKey='id';
    protected $table='unconfirmed_manuals';
    protected $fillable=['manual_name','description'];

    public $timestamps = false;
}
