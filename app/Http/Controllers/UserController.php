<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;
use App\Models\User;
use Firebase\JWT\JWT;

class UserController extends Controller
{
  public function createUser(Request $request)
  {
    $this->validate($request, [
      'password' => 'required',
      'email' => 'required',
  ]);

    $checkIfEmailExists = User::where('email', $request->input('email'))->get();

    if (count($checkIfEmailExists) > 0) {
      throw new ConflictException('Looks like somebody else registered with this email');
    }

    $request['role'] = 'user';
    $createdUser = User::create($request->all());
    $message["response"]["message"] = "Account created successfully";
    $message["response"]["createdUser"] = $createdUser;

    return response()->json($message, 201);
  }

  private function jwt($user) {
    $payload = [
        'iss' => "lumen-jwt",
        'sub' => $user,
        'iat' => time(),
        'exp' => time() + 60*60*24
    ];
    return JWT::encode($payload, env('JWT_SECRET'));
  }

  public function login(Request $request) {

    $this->validate($request, [
      'password' => 'required',
      'email' => 'required',
    ]);

    $emailExists = User::where('email', $request->input('email'))->first();

    if ($emailExists) {
      if (Hash::check($request->input('password'), $emailExists->password)) {
      $userDetail['id'] = $emailExists->id;
      $userDetail['role'] = $emailExists->role;
        return response()->json([
          'token' => $this->jwt($userDetail)
      ], 200);
    }
    throw new BadRequestException('Incorrect details');
    }
    throw new NotFoundException('Looks like you\'ve not registered this account');
  }

  public function getUsers(Request $request) {
    dd('u got the user');
  }
}