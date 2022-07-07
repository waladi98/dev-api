<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/* Exception Handler by SIP */
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
/* Exception Handler by SIP */

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        
        //dd($exception);

        if (env('APP_DEBUG'))
        {
            return parent::render($request, $exception);
        }

        $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof NotResponseException)
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        elseif ($exception instanceof MethodNotAllowedHttpException)
        {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $exception = new MethodNotAllowedHttpException([],'HTTP_METHOD: Yang Digunakan Tidak Sesuai!.',$exception);
        }
        elseif ($exception instanceof NotFoundHttpException)
        {
            $status = Response::HTTP_NOT_FOUND;
            $exception = new MethodNotAllowedHttpException([],'Endpoint Tidak Ditemukan',$exception);
        }
        elseif ($exception instanceof ValidationException && $exception->getResponse())
        {
            $status = Response::HTTP_NO_CONTENT;
            
            return response()->json([
                                     "code"    => $status, 
                                     "message" => $exception->errors(),
                                     "result"  => ["success" => false]
                                    ]);
        }

        return response()->json(["code"    => $status, 
                                 "message" => $exception->getMessage(),
                                 "result"  => ["successx" => false]
                                ]);

    }
}
