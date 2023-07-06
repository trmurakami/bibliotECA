<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function works()
    {
        return $this->belongsToMany(Work::class, 'about_work');
    }
}