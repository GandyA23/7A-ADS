<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' ];

    private const VALIDATIONS = [
        'name' => 'required|string|unique:editorials,name|max:255'
    ];

    public function getValidations () {
        return self::VALIDATIONS;
    }
}
