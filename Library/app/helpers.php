<?php

use Illuminate\Support\Facades\Validator;

/**
 * Validate the requirements of a request,
 * Returns the request messages errors as an array.
 *
 * @param  array  $data
 * @param  array  $validation
 * @param  array  $customMessages
 * @return array
 */
function validate ($data, $validations, $id = 0, $customMessages = []) {
    $messages = [];

    // Add data in case to update in unique fields
    if ($id > 0) {
        foreach ($validations as $field => $validation) {
            // Example of a validation:
            //  'isbn' => 'required|string|max:15|unique:books,isbn'

            $validationsArray =  gettype($validation) !== 'array' ? explode('|', $validation) : $validation;

            // Iterate the array of validations looking for unique validation
            if (isset($data[$field])) {
                foreach ($validationsArray as $key => $condition) {
                    if (str_contains($condition, 'unique')) {
                        // If it contains "_id" then it is a foreign field, otherwise it is the same table
                        $validationsArray[$key] = str_contains($field, '_id')
                            ? str_replace($condition, $condition . ',' . $data[$field], $condition)
                            : str_replace($condition, $condition . ',' . $id, $condition);
                        break;
                    }
                }
                $validations[$field] = $validationsArray;
            }

        }
    }

    // Make the validator
    $validator = Validator::make($data, $validations, $customMessages);

    // Check the messages in case of any errors.
    $fields = $validator->messages()->toArray();

    // Extract the messages in case there was an error
    foreach ($fields as $field) {
        $messages = array_merge($messages, $field);
    }

    return $messages;
}
