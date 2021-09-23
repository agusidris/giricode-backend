<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            //Access token from the request
            $token = JWTAuth::parseToken();
            //Try authenticating user
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            //Thrown if token has expired
            return $this->unauthorized('Token Anda kedaluwarsa. Silakan login lagi.');
        } catch (TokenInvalidException $e) {
            //Thrown if token invalid
            return $this->unauthorized('Token Anda tidak valid. Silakan, login lagi.');
        }catch (JWTException $e) {
            //Thrown if token was not found in the request.
            return $this->unauthorized('Silahkan lampirkan Bearer Token ke request Anda');
        }
        //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return $this->unauthorized();
    }

    private function unauthorized($message = null){
        return response()->json([
            'success' => false,
            'message' => $message ? $message : 'Anda tidak memiliki akses untuk resource ini'
        ], 401);
    }
}
