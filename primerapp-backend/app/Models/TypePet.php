<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $cast = [
        'is_active' => 'boolean'
    ];
}
