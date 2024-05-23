<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class FrontAuth
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
        $member = Session::get('member');
        if (empty($member)) {

            return redirect('front');
        }

        $request->member = $member;

        return $next($request);
    }
}
