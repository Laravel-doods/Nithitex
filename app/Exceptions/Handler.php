<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;

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
        'password',
        'password_confirmation',
    ];

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return response()->json(['status' => "false",'message' => $exception->getMessage()], 401);
    // }




    protected function unauthenticated($request, AuthenticationException $exception)
    {
    if ($request->expectsJson()) {
        return response()->json(['message' => $exception->getMessage()], 401);
    }
    $guard = Arr::get($exception->guards(), 0);
    switch($guard){
    case 'seller':
    $login = 'seller.login';
    break;
    case 'admin':
    $login = 'admin.form';
    break;
    default:
        $login = 'user.login';
        break;
        }
            return redirect()->guest(route($login));
        }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
