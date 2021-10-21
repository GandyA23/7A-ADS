<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;

class CategoryController extends Controller {
    /**
     * Get all categories stored in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $code = 200;
        $status = 0;
        $title = 'Get all categories';
        $message = 'No stored categories';
        $data = array();

        try {
            $data = Category::all();

            if (count($data)) {
                $status = 1;
                $message = 'Succesful get all categories!';
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
     * Store or update a category in database.
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
            $category = new Category();

            // Validate
            $data = validate($requestData, $category->getValidations());
            $hasErrors = count($data);

            if (!empty($id)) {
                // Update
                $title = 'Failed to update!';
                $message = 'Category was not updated';

                if (!$hasErrors) {
                    $category = $category->find($id);

                    if (!empty($category)) {
                        if ($category->update($requestData)) {
                            $status = 1;
                            $code = 201;
                            $title = 'Succesful update!';
                            $message = 'Category updated';
                            $data = $category;
                            DB::commit();
                        }
                    } else {
                        $message = "No stored category with id $id";
                    }
                }

            } else {
                // Store
                $title = 'Failed to store!';
                $message = 'Category was not stored';

                if (!$hasErrors) {
                    $data = $category->create($requestData);

                    if (!empty($data)) {
                        $status = 1;
                        $code = 201;
                        $title = 'Succesful store!';
                        $message = 'Category stored';
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
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $code = 200;
        $status = 0;
        $title = 'Get category';
        $message = "No stored category with id $id";
        $data = array();

        try {
            if (!empty($id)) {
                $data = Category::find($id);

                if (!empty($data)) {
                    $status = 1;
                    $message = 'Succesful get category';
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
        $title = 'Delete category';
        $message = "No stored category with id $id";

        try {
            DB::beginTransaction();
            if (!empty($id)) {
                $category = Category::find($id);

                if (!empty($category) && $category->delete()) {
                    $status = 1;
                    $message = 'Succesful destroy category';
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
