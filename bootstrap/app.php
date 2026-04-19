<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\SetRegionLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: [
            __DIR__ . '/../routes/admin.php',
            __DIR__ . '/../routes/consumer.php',
            __DIR__ . '/../routes/pro.php',
            __DIR__ . '/../routes/web.php',
        ],
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => CheckPermission::class,
            'set.locale' => SetRegionLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'status'  => 401,
                'message' => $e->getMessage()
            ], 401);
        });
        $exceptions->render(function (ValidationException $e) {
            $formattedErrorsArray = [];
            foreach ($e->errors() as $message) {
                $formattedErrorsArray[] = $message[0];
            }
            $formattedErrors = implode('<br>', $formattedErrorsArray);
            return response()->json([
                'status'  => 422,
                'message' => $formattedErrors
            ], 422);
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'status'  => 405,
                'message' => $e->getMessage()
            ], 405);
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'status'  => 404,
                'message' => $e->getMessage()
            ], 404);
        });
        $exceptions->render(function (RouteNotFoundException $e) {
            if ($e->getMessage() == 'Route [login] not defined.') {
                return response()->json([
                    'status'  => 401,
                    'message' => __('error.auth.not_login')
                ], 401);
            }

            return null;
        });
    })->create();
