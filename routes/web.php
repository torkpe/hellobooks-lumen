<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(["prefix" => "api/v1"], function ($router) {
    $router->post("users/signup", ['middleware' => 'signup', "uses"=>"UserController@createUser"]);
    $router->post("login", "UserController@login");
    $router->get("users", ['middleware' => 'admin', "uses"=>"UserController@getUsers"]);
    $router->post("books", ['middleware' => ['admin', ], "uses"=>"BookController@addBook"]);
    $router->get("books", ['middleware' => 'auth', "uses"=>"BookController@getBooks"]);
    $router->get("books/borrow", ['middleware' => 'auth', "uses"=>"BorrowBookController@getBorrowedBooks"]);
    $router->get("books/borrow/{bookId}", ['middleware' => 'auth', "uses"=>"BorrowBookController@getABorrowedBooks"]);
    $router->get("books/{bookId}", ['middleware' => 'auth', "uses"=>"BookController@getABook"]);
    $router->put("books/{bookId}",  ['middleware' => ['admin', 'book'], "uses"=>"BookController@updateBook"]);
    $router->get("books/{bookId}/borrow", ['middleware' => 'auth', "uses"=>"BorrowBookController@borrowBook"]);
    $router->get("books/{bookId}/return", ['middleware' => 'auth', "uses"=>"BorrowBookController@returnBook"]);
});