<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;

    protected $primaryKey='id';
    protected $table='manuals';
    protected $fillable=['manual_name','description'];

    public $timestamps = false;
}
