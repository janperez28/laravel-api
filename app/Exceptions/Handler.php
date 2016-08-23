<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        //return parent::render($request, $e);
        
        $errors = array();
        
        if ($e instanceof HttpException)
        {                      
            $statusCode = $e->getStatusCode();
        
            // We return 400 response for missing/unknown routes since 404
            // response is for non-existing objects 
            ($statusCode == SymfonyResponse::HTTP_NOT_FOUND) and ($statusCode = SymfonyResponse::HTTP_BAD_REQUEST);
            
            // Use the default messages for HTTP exceptions
            $errors = array(SymfonyResponse::$statusTexts[$statusCode]);
        }
        else if ($e instanceof ModelNotFoundException)
        {
            // Return an object not found response for ModelNotFoundException's
            $statusCode = SymfonyResponse::HTTP_NOT_FOUND;
        }
        else 
        {
            // Let's just return a server error status code for any other exception.
            $statusCode = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;
        }
        
        return response()->api(false, $errors)->setStatusCode($statusCode);
    }
}
