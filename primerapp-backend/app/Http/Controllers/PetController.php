<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Method test.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test () {
        $error = false;
        $message = 'Consulta exitosa';
        $status = 200;

        return response()->json([
            'error' => $error,
            'message' => $message
        ], $status);
    }
}
