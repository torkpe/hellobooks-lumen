<?php

namespace App\Http\Controllers;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;
use App\Models\Book;

class BookController extends Controller
{

  public function addBook(Request $request)
  {
    $this->validate($request, [
        "title" => "required|min:2",
        "description" => "required|min:5",
        "quantity" => "numeric",
        "author" => "required|min:2",
        "cover" => "required|min:10",
    ]);

    $createdBook = Book::create($request->all());
    return response()->json($createdBook, 201);
  }

  public function getBooks(Request $request)
  {
    $books = Book::all();
    if(count($books) > 0) {
        return response()->json($books, 200);
    }
    return response()->json((object)["message" => "No book in the library at this time"], 200);
  }

  public function getABook($bookId)
  {
    if (!is_numeric($bookId)) {
      throw new NotFoundException("Invalid book Id");
    }
    $book = Book::where('id', $bookId)->first();
    if ($book) {
      return response()->json($book, 200);
    }
      throw new NotFoundException("Sorry, this book does not exist");
  }

  public function updateBook(Request $request, $bookId)
  {
    if (!is_numeric($bookId)) {
      throw new NotFoundException("Invalid book Id");
    }

    $book= Book::find($bookId);

    if (!$book) {
      throw new NotFoundException("Sorry, this book does not exist");
    }
    $updatedBook = $book->fill($request->all());
    $updatedBook->save();
    return response()->json($updatedBook, 200);
  }
}