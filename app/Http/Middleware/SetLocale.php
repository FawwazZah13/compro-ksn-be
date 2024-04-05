<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            $locale = $request->input('lang');
            app()->setLocale($locale);
            Session::put('locale', $locale);
        } elseif (Session::has('locale')) {
            app()->setLocale(Session::get('locale'));
        }

        return $next($request);
}
}
