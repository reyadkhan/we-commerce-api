<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if($this->isApi($request)) {
                return response()->json(['message' => 'Record not found'], 404);
            }
        });

        $this->renderable(function (UnauthorizedException $e, Request $request) {
            if($this->isApi($request)) {
                return response()->json(['message' => 'Unauthorized request'], 403);
            }
        });
    }

    private function isApi(Request $request): bool
    {
        return $request->is("api/*");
    }
}
