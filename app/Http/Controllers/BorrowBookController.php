<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\ModelConflictException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\NotFoundException;
use App\Exceptions\ConflictException;
use App\Models\Borrows;
use App\Models\Book;

class BorrowBookController extends Controller
{

  public function borrowBook(Request $request, $bookId)
  {
    $bookExists = $this->validateBookDetail($bookId);

    $borrowedBooks = Borrows::where([
        "user_id" => $request->userId,
        "returned" => false,
        "book_id" => $bookId
    ])->get();

    $userId = $request->userId;

    if (count($borrowedBooks) < 1) {
      $result = DB::transaction(
        function () use($userId, $bookId, $bookExists) {
          
          $borrowDetails = [
            "user_id" => $userId,
            "book_id" => $bookId,
            "returned" => false,
            ];

            $borrowedBook = Borrows::create($borrowDetails);
            
            $bookExists->quantity = (int)$bookExists->quantity - 1;

            $bookExists->save();

            return $borrowedBook;
        }
      );
      $response = ["message" => "Successfully borrowed book", "book" => $result];
      return response()->json($response, 200);
    }
    return response()->json(["message" => "Return book before borrowing again"], 404);
  }


  private function validateBookDetail($bookId) 
  {
    if (!is_numeric($bookId)) {
      throw new NotFoundException("Invalid book Id");
    }

    $bookExists = Book::find($bookId);

    if (!$bookExists) {
      throw new NotFoundException("Sorry. This book does not exist");
    }
    if ($bookExists->quantity === 0) {
      return response()->json("No copy of the book exists yet", 401);
    }
    return $bookExists;
  }


  public function getBorrowedBooks(Request $request)
  {

    $borrowedBooks = Borrows::where([
      "user_id" => $request->userId,
      "returned"=> false
    ])->with('book')->get();

    if (count($borrowedBooks) > 0) {
      return response()->json($borrowedBooks, 200);
    }
    return response()->json(["message" => "You have not borrowed any book at this point in time"]);
  }

  public function getABorrowedBooks(Request $request, $bookId)
  {
    $borrowedBooks = Borrows::where([
      "user_id" => $request->userId,
      "returned" => false,
      "book_id" => $bookId
    ])->with('book')->get();

    if (count($borrowedBooks) > 0) {
      return response()->json($borrowedBooks, 200);
    }
    return response()->json(["message" => "You have not borrowed this book at this point in time", 404]);
  }

  public function returnBook(Request $request, $bookId)
  {
    $bookExists = $this->validateBookDetail($bookId);

    $borrowedBooks = Borrows::where([
        "user_id" => $request->userId,
        "returned" => false,
        "book_id" => $bookId
    ])->get();

    $userId = $request->userId;

    if (count($borrowedBooks) > 0) {
      $result = DB::transaction(
        function () use($bookExists, $borrowedBooks) {
          
          $returnDetails = [
            "returned" => true,
            ];

            $borrowedBooks->first()->returned = true;
            $borrowedBooks->first()->save();

            $bookExists->quantity = (int)$bookExists->quantity + 1;
            $returned = $bookExists->save();

            return $bookExists;
        }
      );
      $response = ["message" => "Successfully returned book", "book" => $result];
      return response()->json($response, 200);
    }
    return response()->json(["message" => "Borrow book before returning"], 404);
  }
}
