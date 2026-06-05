<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->reportable(function (Throwable $e) {
            if (app()->bound('request')) {
                $request = request();

                logger()->error('Highbrows request exception', [
                    'message' => $e->getMessage(),
                    'exception_class' => get_class($e),
                    'url' => $request->fullUrl(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'route_name' => optional($request->route())->getName(),
                    'ip' => $request->ip(),
                    'user_id' => optional($request->user())->id,
                    'session_id' => optional($request->session())->getId(),
                    'input' => $request->except(['password', 'password_confirmation', 'current_password']),
                ]);
            }
        });
    }
}
