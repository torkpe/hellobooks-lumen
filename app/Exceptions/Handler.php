<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ConflictException) {
            return $response = $this->composeJsonResponse(
                Response::HTTP_CONFLICT,
                $e->getMessage()
            );
        }
        if ($e instanceof NotFoundException) {
            return $response = $this->composeJsonResponse(
                Response::HTTP_NOT_FOUND,
                $e->getMessage()
            );
        }
        if ($e instanceof BadRequestException) {
            return $response = $this->composeJsonResponse(
                Response::HTTP_BAD_REQUEST,
                $e->getMessage()
            );
        }
        return parent::render($request, $e);
    }
    /**
     * Compose http json responses
     *
     * @param  $header
     * @param  $message
     * @return \Illuminate\Http\Response
     */
     private function composeJsonResponse($header, $message)
     {
         return response()->json(["message" => $message], $header);
     }
}
