<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Closure;

class SignupMiddleware
{
  /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
  
  public function handle(Request $request, Closure $next)
  {

    if (!$request->input('email')) {
      $errorMessage['message'] = 'email is required';
      return response()->json($errorMessage, 400);
    }
    if (!$request->input('password')) {
      $errorMessage['message'] = 'password is required';
      return response()->json($errorMessage, 400);
    }
    if (!$request->input('confirmPassword')) {
      $errorMessage['message'] = 'Confirm Password is required';
      return response()->json($errorMessage, 400);
    }
    if ($request->input('password') !== $request->input('confirmPassword')) {
      $errorMessage['message'] = 'passwords do not match';
      return response()->json($errorMessage, 400);
    }
    return $next($request);
  }
}