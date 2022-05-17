<?php

namespace App\Http\Middleware;

use App\MyModels\GoogleRecaptcha;
use Closure;

class CheckIfHuman
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
        if($request->has('g-recaptcha-response')){
            $isVerified = GoogleRecaptcha::verify($request->{'g-recaptcha-response'});
            if($isVerified)
                return $next($request);
        }
        return abort(403);

    }
}
