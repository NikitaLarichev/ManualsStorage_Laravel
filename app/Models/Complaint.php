<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $primaryKey='id';
    protected $table='complaints';
    protected $fillable=['claim'];
    
    public $timestamps = false;
}
