<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [  'name', 'last_name', 'mother_last_name' ];

    private const VALIDATIONS = [
        'name' => 'required|string|max:45',
        'last_name' => 'required|string|max:45',
        'mother_last_name' => 'nullable|string|max:45',
        'books' => 'nullable|array|min:1',
        'books.*' => 'nullable|integer|min:1'
    ];

    public function getValidations () {
        return self::VALIDATIONS;
    }

	public function booksSortedByPubDate ($filter = 'desc') {
		return $this->books()->orderBy('publication_date', $filter);
	}

    public function books () {
        return $this->belongsToMany(Book::class);
    }
}
