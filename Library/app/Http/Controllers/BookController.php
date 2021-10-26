<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Book;

class BookController extends Controller {
    /**
     * Get all books stored in database.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $code = 200;
        $status = 0;
        $title = 'Get all books';
        $message = 'No stored books';
        $data = array();

        try {
            $data = Book::all();

            if (count($data)) {
                $status = 1;
                $message = 'Successful get all books!';
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
     * Store or update a book in database.
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
            $book = new Book();

            // Validate
            $data = validate($requestData, $book->getValidations(), $id);
            $hasErrors = count($data);

            if (!empty($id)) {
                // Update
                $title = 'Failed to update!';
                $message = 'Book was not updated';

                if (!$hasErrors) {
                    $book = $book->find($id);

                    if (!empty($book) && $book->update($requestData)) {
                        $status = 1;
                        $code = 201;
                        $title = 'Successful update!';
                        $message = 'Book updated';
                        $data = $book;
                        DB::commit();
                    } else {
                        $message = "No stored book with id $id";
                    }
                }

            } else {
                // Store
                $title = 'Failed to store!';
                $message = 'Book was not stored';

                if (!$hasErrors) {
                    $data = $book->create($requestData);

                    if (!empty($data)) {
                        $status = 1;
                        $code = 201;
                        $title = 'Successful store!';
                        $message = 'Book stored';
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
     * Display the specified book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $code = 200;
        $status = 0;
        $title = 'Get book';
        $message = "No stored book with id $id";
        $data = array();

        try {
            if (!empty($id)) {
                $data = Book::with(['category', 'editorial', 'authors'])->find($id);

                if (!empty($data)) {
                    $status = 1;
                    $message = 'Successful get book';
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
        $title = 'Delete book';
        $message = "No stored book with id $id";

        try {
            DB::beginTransaction();
            if (!empty($id)) {
                $book = Book::find($id);

				if (!empty($book)) {
                    $noAuthors = $book->authors()->count();
					if ($noAuthors) {
                        $title = 'Error deleting book';
                        $message = "This book has $noAuthors author(s)";
					} else if ($book->delete()) {
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
