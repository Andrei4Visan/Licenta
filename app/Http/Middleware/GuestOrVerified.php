<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// Asigură-te că utilizezi clasa corectă de la Laravel
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;

class GuestOrVerified extends BaseEnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  ?string  $redirectToRoute (optional)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        // Verifică dacă utilizatorul nu este logat, dacă este așa, doar trece mai departe
        if (!$request->user()) {
            return $next($request);
        }

        // Altfel, aplică logica middleware-ului de verificare a email-ului
        return parent::handle($request, $next, $redirectToRoute);
    }
}
