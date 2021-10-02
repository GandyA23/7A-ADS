<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model {
    use HasFactory;

    protected $fillable = [
        'breed',
        'size',
        'type_pet_id'
    ];

    // Validaciones para llenar los campos en la tabla
    private const VALIDATE = [
        'breed' => 'nullable|string',
        'size' => 'required|string'
    ];

    // Mensajes de error en caso de que alguna validación no se cumpla
    private const CUSTOM_VALIDATION_MESSAGES = [
        'size.required' => "El campo 'tamaño' es obligatorio y no debe de ser nulo."
    ];

    /**
     * Obtiene el arreglo que contiene las validaciones
     * para validar el Objeto \Illuminate\Http\Request
     *
     * @return array
     */
    public function getValidate () {
        return self::VALIDATE;
    }

    /**
     * Obtiene el arreglo que contiene los mensajes en caso de
     * que surja algún error en las validaciones del Objeto
     * \Illuminate\Http\Request
     *
     * @return array
     */
    public function getCustomValidationMessages () {
        return self::CUSTOM_VALIDATION_MESSAGES;
    }
}
