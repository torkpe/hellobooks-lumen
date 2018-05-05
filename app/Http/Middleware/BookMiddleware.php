<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class BookMiddleware
{
  /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
  
  public function handle($request, Closure $next)
  {
    if (!$this->trimRequestBody($request->input('title'))) {
      $errorMessage['message'] = 'Title Field is required';
      return response()->json($errorMessage, 400);
    }
    if (!$this->trimRequestBody($request->input('author'))) {
      $errorMessage['message'] = 'Author field is required';
      return response()->json($errorMessage, 400);
    }
    if (!$this->trimRequestBody($request->input('description'))) {
      $errorMessage['message'] = 'Description field is required';
      return response()->json($errorMessage, 400);
    }
    if (!$this->trimRequestBody($request->input('quantity'))) {
      $errorMessage['message'] = 'Quantity field is required';
      return response()->json($errorMessage, 400);
    }
    if (!$this->trimRequestBody($request->input('cover'))) {
      $errorMessage['message'] = 'Cover field is required';
      return response()->json($errorMessage, 400);
    }
    return $next($request);
  }

  private function trimRequestBody($request) {
    return trim(preg_replace('/\s+/',' ', $request));
  }
}
