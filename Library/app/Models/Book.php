<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'description',
        'publication_date',
        'editorial_id',
        'category_id'
    ];

    protected $cast = [
        'books.publication_date' => 'date:Y-m-d'
    ];

    private const VALIDATIONS = [
        'isbn' => 'required|string|max:15|unique:books,isbn',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'publication_date' => 'nullable|string',
        'editorial_id' => 'required|integer',
        'category_id' => 'required|integer',
        'authors' => 'nullable|array|min:1',
        'authors.*' => 'nullable|integer|min:1'
    ];

    public function getValidations () {
        return self::VALIDATIONS;
    }

    public function authors () {
        return $this->belongsToMany(Author::class);
    }

    public function category () {
        return $this->belongsTo(Category::class);
    }

    public function editorial () {
        return $this->belongsTo(Editorial::class);
    }
}
