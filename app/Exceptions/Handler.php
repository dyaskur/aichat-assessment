<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport
        = [
            //
        ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash
        = [
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
        $this->reportable(function(Throwable $e) {
            //
        });
    }

    public function render(
        $request,
        Exception|Throwable $e
    ): \Illuminate\Http\Response|JsonResponse|Response {
        if ($e instanceof FailResponse) {
            return $this->returnFailResponse($e->getMessage(), $e->getCode());
        }

        return parent::render($request, $e);
    }

    protected function returnFailResponse(string $message, int $status): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
