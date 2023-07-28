<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{

    //use HasFactory;
    
    protected $fillable = ['type', 'idLattes13', 'name', 'affiliation'];

    protected $casts = [
        'affiliation' => 'array',
    ];

    public function works()
    {
        return $this->belongsToMany(Work::class, 'thing_work');
    }
}