<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;

/**
 * Este modelo tiene como propósito realizar
 * las validaciones de los campos de cada
 * función del controlador
 */

class ValidateRequest {
    /**
     * Valida todos los requisitos que son ingresados en $validation,
     * en caso de algún error, retornará un arreglo de strings
     * indicando los errores, en caso contrario, retorna un arreglo vacío.
     *
     * @param  array  $data
     * @param  array  $validation
     * @param  array  $customMessages
     * @return array
     */
    public static function validate ($data, $validation, $customMessages = []) {
        $messages = [];

        // Crea el validador
        $validator = Validator::make($data, $validation, $customMessages);

        // Consulta los mensajes en caso de algún error
        $fields = $validator->messages()->toArray();

        // Extrae los mensajes de los campos donde hubó algún error
        foreach ($fields as $field) {
            $messages = array_merge($messages, $field);
        }

        return $messages;
    }
}
