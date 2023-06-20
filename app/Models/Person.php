<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
}

class Person extends Model
{
    protected $fillable = ['name'];

    public function works()
    {
        return $this->belongsToMany(Work::class, 'person_work');
    }
}