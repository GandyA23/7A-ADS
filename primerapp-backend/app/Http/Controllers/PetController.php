<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Pet;
use App\Models\ValidateRequest;

use Exception;

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

    /**
     * Store a newly created pet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request) {
        // Variables para retornar al final en un json, cambiarán de valor por el camino
        $data = [];
        $error = true;
        $message = 'Error al guardar la mascota';
        $status = 400;

        // Inicia una transacción
        DB::beginTransaction();

        try {
            $pet = new Pet();
            $data = $request->all();

            // Realiza la validación de los campos que enviaron
            $errorsMessages = ValidateRequest::validate($data, $pet->getValidate(), $pet->getCustomValidationMessages());

            // En caso de que no haya ningún mensaje, entonces continúa
            if (count($errorsMessages) < 1) {

                // Guarda los datos en la tabla
                $data = $pet->create($data);

                // Si se guardaron los datos, entonces cambia las variables para su retorno
                if (!empty($data)) {
                    $status = 201;
                    $error = false;
                    $message = 'Mascota registrada correctamente';

                    // Realiza el commit
                    DB::commit();
                }
            } else {
                // En caso contrario, consulta los mensajes y devuelvelos en la data
                $data = $errorsMessages;
            }

        } catch (Exception $e) {
            // En caso de que haya surgido algún error durante el proceso de la función,
            // entonces cambia las variables a su estado inicial y devuelve la razón
            $status = 500;
            $error = true;
            $message = 'Error al guardar la mascota';
            $data = $e->getMessage();

            DB::rollBack();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Update the specified pet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update (Request $request, $id) {
        $data = [];
        $error = true;
        $message = 'Error al actualizar la mascota';
        $status = 400;

        // Inicia una transacción
        DB::beginTransaction();

        try {
            $pet = Pet::find($id);
            $data = $request->all();

            // Verifica que exista un dato con ese id
            if (!empty($pet)) {
                // Realiza la validación de los campos que enviaron
                $errorsMessages = ValidateRequest::validate($data, $pet->getValidate(), $pet->getCustomValidationMessages());

                // En caso de que no haya ningún mensaje, entonces continúa
                if (count($errorsMessages) < 1) {

                    // Actualiza los datos en la tabla
                    $data = $pet->update($data);

                    // Si se actualizaron los datos, entonces cambia las variables para su retorno
                    if (!empty($data)) {
                        $data = $pet;
                        $status = 200;
                        $error = false;
                        $message = 'Mascota actualizada correctamente';

                        // Realiza el commit
                        DB::commit();
                    }
                } else {
                    // En caso contrario, consulta los mensajes y devuelvelos en la data
                    $data = $errorsMessages;
                }
            } else {
                // Si no existe algún dato con ese id, entonces devuelve un mensaje de error
                $data = "La mascota con el id $id no existe.";
                $status = 404;
            }


        } catch (Exception $e) {
            // En caso de que haya surgido algún error durante el proceso de la función,
            // entonces cambia las variables a su estado inicial y devuelve la razón
            $status = 500;
            $error = true;
            $message = 'Error al actualizar la mascota';
            $data = $e->getMessage();

            DB::rollBack();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Get the specified pet by id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get ($id){
        $data = [];
        $error = true;
        $message = 'Error al consultar la mascota';
        $status = 400;

        try {
            $pet = Pet::find($id);

            // Verifica que exista un dato con ese id
            if (!empty($pet)) {
                // Asigna las variables de regreso para el response
                $data = $pet;
                $status = 200;
                $error = false;
                $message = 'Mascota consultada correctamente';
            } else {
                // Si no existe algún dato con ese id, entonces devuelve un mensaje de error
                $data = "La mascota con el id $id no existe.";
                $status = 404;
            }
        } catch (Exception $e) {
            // En caso de que haya surgido algún error durante el proceso de la función,
            // entonces cambia las variables a su estado inicial y devuelve la razón
            $status = 500;
            $error = true;
            $message = 'Error al consultar la mascota';
            $data = $e->getMessage();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Get all pets.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll () {
        $data = [];
        $error = true;
        $message = 'Error al consultar todas las mascotas';
        $status = 400;

        try {
            $pets = Pet::all();

            // Verifica que existen mascotas en la base de datos
            if (!empty($pets)) {
                $data = $pets;
                $status = 200;
                $error = false;
                $message = 'Mascotas consultadas correctamente';
            } else {
                // Si no existe algún dato con ese id, entonces devuelve un mensaje de error
                $data = "No hay mascotas guardadas";
            }
        } catch (Exception $e) {
            // En caso de que haya surgido algún error durante el proceso de la función,
            // entonces cambia las variables a su estado inicial y devuelve la razón
            $status = 500;
            $error = true;
            $message = 'Error al consultar todas las mascotas';
            $data = $e->getMessage();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Remove the specified pet from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id) {
        $data = [];
        $error = true;
        $message = 'Error al eliminar la mascotas';
        $status = 400;

        try {
            // Inicia una transacción
            DB::beginTransaction();

            $pet = Pet::find($id);

            // Verifica que exista un dato con ese id
            if (!empty($pet)) {
                // Elimina el dato de las bases de datos
                $error = $pet->delete();

                if ($error) {
                    // Si se eliminó, entonces asigna las variables de retorno
                    $status = 200;
                    $data = "La mascota con el id $id se ha eliminado";
                    $message = 'Mascota eliminada correctamente';
                    $error = false;

                    // Realiza el commit
                    DB::commit();
                }

            } else {
                // Si no existe algún dato con ese id, entonces devuelve un mensaje de error
                $data = "La mascota con el id $id no existe.";
                $status = 404;
            }
        } catch (Exception $e) {
            // En caso de que haya surgido algún error durante el proceso de la función,
            // entonces cambia las variables a su estado inicial y devuelve la razón
            $status = 500;
            $error = true;
            $message = 'Error al eliminar la mascota';
            $data = $e->getMessage();

            DB::rollBack();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
