<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{

    //use HasFactory;
    
    protected $fillable = ['type', 'id_lattes13', 'name'];

    public function works()
    {
        return $this->belongsToMany(Work::class, 'thing_work');
    }
}