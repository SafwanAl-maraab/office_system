<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RoleMiddleware;

use Illuminate\Http\Request;

use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([

            'role' => RoleMiddleware::class,

            'permission' => PermissionMiddleware::class,

            'role_or_permission' => RoleOrPermissionMiddleware::class,

        ]);

    })

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (
            UnauthorizedException $e,
            Request $request
        ) {

            $user = auth()->user();

            if (! $user) {

                return redirect()
                    ->route('login');

            }

            $message =
                'ليس لديك صلاحية للوصول إلى هذه الصفحة';

            /*
            |--------------------------------------------------------------------------
            | أفضل صفحة للمستخدم
            |--------------------------------------------------------------------------
            */

            if ($user->can('view.dashboard')) {

                return redirect()
                    ->route('dashboard')
                    ->with('error', $message);

            }

            if ($user->can('view.bookings')) {

                return redirect()
                    ->route('bookings.index')
                    ->with('error', $message);

            }

            if ($user->can('view.visas')) {

                return redirect()
                    ->route('visas.index')
                    ->with('error', $message);

            }

            if ($user->can('view.clients')) {

                return redirect()
                    ->route('clients.index')
                    ->with('error', $message);

            }

            return redirect('/')
                ->with('error', $message);

        });

    })

    ->create();

