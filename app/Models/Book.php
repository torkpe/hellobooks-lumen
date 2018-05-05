<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Book extends Model
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author', 'description', 'quantity', "cover"
    ];
    public $rules = [
        "title" => "required|min:3",
        "description" => "required|min:5",
        "quantity" => "numeric",
        "author" => "required|min:2",
        "cover" => "required|min:10",
    ];
}
