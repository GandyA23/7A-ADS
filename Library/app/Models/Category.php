<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;

    protected $fillable = [ 'name' ];

    private const VALIDATIONS = [
        'name' => 'required|string|unique:categories,name|max:45'
    ];

    public function getValidations () {
        return self::VALIDATIONS;
    }
}
