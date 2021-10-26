<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Editorial;

class EditorialController extends Controller {
    /**
     * Get all editorials stored in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $code = 200;
        $status = 0;
        $title = 'Get all editorials';
        $message = 'No stored editorials';
        $data = array();

        try {
            $data = Editorial::all();

            if (count($data)) {
                $status = 1;
                $message = 'Successful get all editorials!';
            }
        } catch (\Exception $e) {
            $code = 500;
            $status = 0;
            $title = 'Exception Error!';
            $message = $e->getMessage();
            $data = array();
        }

        return response()->json(array(
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ), $code);
    }

    /**
     * Store or update a editorial in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request, $id = null) {
        $code = 200;
        $status = 0;
        $data = array();

        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $editorial = new Editorial();

            // Validate
            $data = validate($requestData, $editorial->getValidations(), $id);
            $hasErrors = count($data);

            if (!empty($id)) {
                // Update
                $title = 'Failed to update!';
                $message = 'Editorial was not updated';

                if (!$hasErrors) {
                    $editorial = $editorial->find($id);

                    if (!empty($editorial)) {
                        if ($editorial->update($requestData)) {
                            $status = 1;
                            $code = 201;
                            $title = 'Successful update!';
                            $message = 'Editorial updated';
                            $data = $editorial;
                            DB::commit();
                        }
                    } else {
                        $message = "No stored editorial with id $id";
                    }
                }

            } else {
                // Store
                $title = 'Failed to store!';
                $message = 'Editorial was not stored';

                if (!$hasErrors) {
                    $data = $editorial->create($requestData);

                    if (!empty($data)) {
                        $status = 1;
                        $code = 201;
                        $title = 'Successful store!';
                        $message = 'Editorial stored';
                        DB::commit();
                    }
                }
            }
        } catch (\Exception $e) {
            $code = 500;
            $status = 0;
            $title = 'Exception Error!';
            $message = $e->getMessage();
            $data = array();
            DB::rollBack();
        }

        return response()->json(array(
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ), $code);
    }

    /**
     * Display the specified editorial.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $code = 200;
        $status = 0;
        $title = 'Get editorial';
        $message = "No stored editorial with id $id";
        $data = array();

        try {
            if (!empty($id)) {
                $data = Editorial::find($id);

                if (!empty($data)) {
                    $status = 1;
                    $message = 'Successful get editorial';
                } else {
                    $data = array();
                }
            }
        } catch (\Exception $e) {
            $code = 500;
            $status = 0;
            $title = 'Exception Error!';
            $message = $e->getMessage();
            $data = array();
        }

        return response()->json(array(
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ), $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id) {
        $code = 200;
        $status = 0;
        $title = 'Delete editorial';
        $message = "No stored editorial with id $id";

        try {
            DB::beginTransaction();
            if (!empty($id)) {
                $editorial = Editorial::find($id);

                if (!empty($editorial) && $editorial->delete()) {
                    $status = 1;
                    $message = 'Successful destroy editorial';
                    DB::commit();
                }
            }
        } catch (\Exception $e) {
            $code = 500;
            $status = 0;
            $title = 'Exception Error!';
            $message = $e->getMessage();
            DB::rollBack();
        }

        return response()->json(array(
            'status' => $status,
            'title' => $title,
            'message' => $message
        ), $code);
    }
}
