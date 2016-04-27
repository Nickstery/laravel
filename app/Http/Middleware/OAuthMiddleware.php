<?php

namespace App\Http\Middleware;

use App\User as User;
use Closure;

class OAuthMiddleware
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

        $token = $request->header('token');
        $userData = last(explode('.',$token));
        $data = json_decode(base64_decode($userData));

        $userEmail = $data->user->email;

        if(empty($userEmail)){
            return response()->json(['code' => 401, 'message' => 'Unauthorized request.'], 401);
        }

        $user = User::query()->where('email', '=', $userEmail)->where('token', '=', $token)->first();

        if(!$user){
            return response()->json(['code' => 401, 'message' => 'Unauthorized request.'], 401);
        }

        $request->route()->setParameter('App\User', $user);
        return $next($request);
    }
}
