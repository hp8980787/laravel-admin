<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Helpers\Api\ExceptionReport;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            // 将方法拦截到自己的ExceptionReport
            $reporter = \App\Helpers\Api\ExceptionReport::make($exception);
            if ($reporter->shouldReturn()){
                return $reporter->report();
            }
            if(env('APP_DEBUG')){
                //开发环境，则显示详细错误信息
                return parent::render($request, $exception);
            }else{
                //线上环境,未知错误，则显示500
                return $reporter->prodReport();
            }

        }
        return parent::render($request, $exception);
    }
}
