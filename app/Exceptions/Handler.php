<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Gestion personnalisée des erreurs 404
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'message' => 'La page que vous recherchez n\'existe pas.'
            ], 404);
        }

        // Gestion des erreurs 403 (Forbidden)
        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 403) {
            return response()->view('errors.403', [
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        // Gestion des erreurs 500
        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 500) {
            return response()->view('errors.500', [
                'message' => 'Une erreur interne du serveur s\'est produite.'
            ], 500);
        }

        // Gestion des erreurs de validation
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // Gestion des modèles non trouvés
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [
                'message' => 'La ressource demandée n\'a pas été trouvée.'
            ], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Non authentifié.'], 401)
            : redirect()->guest(route('login'));
    }
}