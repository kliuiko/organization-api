<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            return $this->handleException($e);
        });
    }

    private function handleException(Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ValidationException => $this->errorResponse('Ошибка валидации', 422, $e->errors()),
            $e instanceof ModelNotFoundException => $this->errorResponse('Запрашиваемый ресурс не найден', 404),
            $e instanceof AuthenticationException => $this->errorResponse('Неавторизованный доступ', 401),
            $e instanceof HttpException => $this->errorResponse(
                $e->getMessage() ?: 'Ошибка сервера',
                $e->getStatusCode()
            ),
            default => $this->errorResponse(
                'Внутренняя ошибка сервера',
                500,
                config('app.debug') ? ['error' => $e->getMessage()] : []
            ),
        };
    }

    private function errorResponse(string $message, int $status, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors ?: null,
        ], $status);
    }
}
