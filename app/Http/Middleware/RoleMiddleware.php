<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // cek login
        if (!auth()->check()) {

            abort(
                403,
                'Silakan login terlebih dahulu.'
            );

        }


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN ACCESS
        |--------------------------------------------------------------------------
        |
        | Administrator dianggap sebagai admin
        |
        */

        if (
            strtolower($user->role) == 'admin'
            ||
            strtolower($user->role) == 'administrator'
        ) {

            return $next($request);

        }



        /*
        |--------------------------------------------------------------------------
        | CEK ROLE LAIN
        |--------------------------------------------------------------------------
        */

        $allowedRoles = array_map(
            'strtolower',
            $roles
        );


        if (
            !in_array(
                strtolower($user->role),
                $allowedRoles
            )
        ) {

            abort(
                403,
                'Anda tidak memiliki hak akses.'
            );

        }



        return $next($request);

    }

}