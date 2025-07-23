<?php

namespace myatKyawThu\LaravelApiVisibility\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use myatKyawThu\LaravelApiVisibility\Exceptions\ApiVisibilityException;
use myatKyawThu\LaravelApiVisibility\Exceptions\RouteParameterMissingException;
use Throwable;

class ErrorHandler
{
    /**
     * Handle the exception and return a formatted response.
     *
     * @param \Throwable $exception
     * @return array
     */
    public function handle(Throwable $exception): array
    {
        if ($exception instanceof ApiVisibilityException) {
            return $this->handleApiVisibilityException($exception);
        }

        return $this->handleGenericException($exception);
    }

    /**
     * Handle an API Visibility exception.
     *
     * @param \myatKyawThu\LaravelApiVisibility\Exceptions\ApiVisibilityException $exception
     * @return array
     */
    protected function handleApiVisibilityException(ApiVisibilityException $exception): array
    {
        $result = [
            'error' => true,
            'message' => $exception->getMessage(),
            'suggestions' => $exception->getSuggestions(),
            'docs_link' => $exception->getDocsLink(),
            'details' => config('app.debug') ? [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ] : null,
            'status' => $this->getStatusCode($exception),
        ];

        // Add missing parameters for RouteParameterMissingException
        if ($exception instanceof RouteParameterMissingException) {
            $result['missing_parameters'] = $exception->getMissingParameters();
        }

        return $result;
    }

    /**
     * Handle a generic exception.
     *
     * @param \Throwable $exception
     * @return array
     */
    protected function handleGenericException(Throwable $exception): array
    {
        $message = config('app.debug')
            ? $exception->getMessage()
            : 'An error occurred while processing your request.';

        $suggestions = [
            'Check your application logs for more details.',
            'Ensure your API controller is functioning correctly.',
            'Verify that all required dependencies are available.',
        ];

        if ($exception instanceof \Illuminate\Database\QueryException) {
            $suggestions = [
                'Check your database connection.',
                'Verify that the required tables exist.',
                'Ensure your database queries are correct.',
                'Check for any missing columns or relationships.',
            ];
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $suggestions = [
                'Log in to your application to access this route.',
                'Ensure you have the proper authentication middleware.',
                'Check if your session is still valid.',
            ];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $suggestions = [
                'Check if the route exists in your application.',
                'Verify that the URL is correct.',
                'Ensure that all required parameters are provided.',
            ];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $suggestions = [
                'Check if you are using the correct HTTP method (GET, POST, PUT, DELETE).',
                'Verify that the route supports the method you are using.',
            ];
        }

        return [
            'error' => true,
            'message' => $message,
            'suggestions' => $suggestions,
            'details' => config('app.debug') ? [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ] : null,
            'status' => $this->getStatusCode($exception),
        ];
    }

    /**
     * Get the HTTP status code for the exception.
     *
     * @param \Throwable $exception
     * @return int
     */
    protected function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return 401;
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return 422;
        }

        if ($exception instanceof RouteParameterMissingException) {
            return 422;
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Format the exception as a response.
     *
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function renderResponse(Throwable $exception)
    {
        $error = $this->handle($exception);

        if (request()->expectsJson()) {
            return new JsonResponse($error, $error['status']);
        }

        return response()->view('api-visibility::error', [
            'error' => $error,
        ], $error['status']);
    }
}
