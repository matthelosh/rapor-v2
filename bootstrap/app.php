<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        channels: __DIR__ . "/../routes/channels.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->web(
                append: [
                    \App\Http\Middleware\HandleInertiaRequests::class,
                    \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
                ],
            )
            ->alias([
                "role" => \Spatie\Permission\Middleware\RoleMiddleware::class,
                "permission" =>
                    \Spatie\Permission\Middleware\PermissionMiddleware::class,
                "role_or_permission" =>
                    \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
                "verify_api_key" => \App\Http\Middleware\ApiKeyVerified::class,
                "auth.bearer" => \App\Http\Middleware\CheckBearerToken::class,
            ]);

        //
        $middleware->api(
            append: [\App\Http\Middleware\LogApiMiddleware::class],
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (
            UnauthorizedException $e,
            Request $request,
        ) {
            return Inertia::render("Error", [
                "status" => $e->getStatusCode(),
                "message" => $e->getMessage(),
            ])
                ->toResponse($request)
                ->setStatusCode($e->getStatusCode());
        });

        // $exceptions->respond(function (Response $response) {
        //     $statusCode = $response->getStatusCode();
        //     return Inertia::render('Error', [
        //         'status' => $statusCode,
        //     ]);

        //     return $response;
        // });
    })
    ->create();
