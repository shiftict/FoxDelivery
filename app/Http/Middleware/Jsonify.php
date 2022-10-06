<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class Jsonify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        $lang = $request->header('X-localization');

        if (!$lang) {
            App::setLocale('ar');
        }

        $lang == 'en'
            ? App::setLocale('en') && Carbon::setLocale('en')
            : App::setLocale('ar') && Carbon::setLocale('ar');

        return $next($request);
    }
}
