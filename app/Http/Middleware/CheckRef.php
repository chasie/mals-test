<?php

namespace App\Http\Middleware;

use Closure;
use App\RefferalLinks;

class CheckRef
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
        if ($request->is('ref/*')) {
            $refId = $request->ref_id;
            $link = RefferalLinks::where('url',$refId)
                ->where('status',RefferalLinks::STATUS_ACTIVE)->first();
            if ($link) {
                $refId = $link;
                $link->hits++;
                $link->save();
                $response = $next($request);
                return $response->withCookie(cookie()->forever('ref_id', $link->id));
            }
        }
        return $next($request);
    }
}
