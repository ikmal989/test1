<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $custom_token = $request->header("PcariComponentToken");
        $token = "Pcari\$ecretTok3n!!";
        $valid = isset($custom_token) && $custom_token == $token;
        if ($valid) {
            return $next($request);
        } else {
            return response()->json(
                [
                    "status" => false,
                    "error" => "Invalid request, required header of
                        [PcariComponentToken]",
                ],
                503
            );
        }
    }
}
