<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\Traits\ApiResponseTraits;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTraits;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        InvalidRequestException::class
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
        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (ValidationException $e) {
            $errors = $e->errors();
            throw new InvalidRequestException(is_array($errors) ? array_values($errors)[0][0] : $errors);
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return $this->failed('权限错误', 403);
        });

        $this->renderable(function (AuthenticationException $e) {
            return $this->failed('您的账号未登录或已退出登录', 401);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->failed('资源不存在', 404);
        });

        $this->renderable(function (Throwable $e) {
            $environment = app()->environment();
            return $this->failed($environment === 'production' ? '服务器错误' : $e->getMessage(), 500);
        });
    }
}
