<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Author;

class AuthorController extends Controller {
    /**
     * Get all authors stored in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $code = 200;
        $status = 0;
        $title = 'Get all authors';
        $message = 'No stored authors';
        $data = array();

        try {
            $data = Author::all();

            if (count($data)) {
                $status = 1;
                $message = 'Successful get all authors!';
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
     * Store or update a author in database.
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
            $author = new Author();

            // Validate
            $data = validate($requestData, $author->getValidations(), $id);
            $hasErrors = count($data);

            if (!empty($id)) {
                // Update
                $title = 'Failed to update!';
                $message = 'Author was not updated';

                if (!$hasErrors) {
                    $author = $author->find($id);

                    if (!empty($author) && $author->update($requestData)) {
						// Detach all books (Drop data in table pivot)
						$author->books()->detach();

						// Attach books (save in table pivot)
                        if (isset($requestData['books'])) {
                            $author->books()->attach($requestData['books']);
                        }

                        $status = 1;
                        $code = 201;
                        $title = 'Successful update!';
                        $message = 'Author updated';
                        $data = $author;
                        DB::commit();
                    } else {
                        $message = "No stored author with id $id";
                    }
                }

            } else {
                // Store
                $title = 'Failed to store!';
                $message = 'Author was not stored';

                if (!$hasErrors) {
                    $data = $author->create($requestData);

                    if (!empty($data)) {
						// Attach books (save in table pivot)
						if (isset($requestData['books'])) {
                            $author->books()->attach($requestData['books']);
                        }

                        $status = 1;
                        $code = 201;
                        $title = 'Successful store!';
                        $message = 'Author stored';
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
     * Display the specified author.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $code = 200;
        $status = 0;
        $title = 'Get author';
        $message = "No stored author with id $id";
        $data = array();

        try {
            if (!empty($id)) {
                $data = Author::with('booksSortedByPubDate')->find($id);

                if (!empty($data)) {
                    $status = 1;
                    $message = 'Successful get author';
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
        $title = 'Delete author';
        $message = "No stored author with id $id";

        try {
            DB::beginTransaction();
            if (!empty($id)) {
                $author = Author::find($id);

				if (!empty($author)) {
                    $noBooks = $author->books()->count();
					if ($noBooks) {
                        $title = 'Error deleting author';
                        $message = "This author has $noBooks book(s)";
					} else if ($author->delete()) {
                        $status = 1;
                        $message = 'Successful destroy';
                        DB::commit();
                    }
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
