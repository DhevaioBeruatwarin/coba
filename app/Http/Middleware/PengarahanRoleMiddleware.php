<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengarahanRoleMiddleware
{
    /**

     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $role = auth()->user()->role;

            switch ($role) {
                case 'Seniman':
                    return redirect('Seniman\dashboard');
                case 'Admin':
                    return redirect('Admin/dashboard');

                default:
                    return redirect('/')->with("error", "user tidak dikenali");
            }
        } else
            return redirect('/login')->with("login dulu mazze");
    }
}
