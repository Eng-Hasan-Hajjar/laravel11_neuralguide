<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class AdminMiddleware {
    public function handle(Request $request, Closure $next): Response {
        abort_unless($request->user()?->isAdmin(), 403, 'هذه الصفحة مخصصة للمدير فقط.');
        return $next($request);
    }
}
