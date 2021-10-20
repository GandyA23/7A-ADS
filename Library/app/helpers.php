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
function validate ($data, $validation, $customMessages = []) {
    $messages = [];

    // Make the validator
    $validator = Validator::make($data, $validation, $customMessages);

    // Check the messages in case of any errors.
    $fields = $validator->messages()->toArray();

    // Extract the messages in case there was an error
    foreach ($fields as $field) {
        $messages = array_merge($messages, $field);
    }

    return $messages;
}
