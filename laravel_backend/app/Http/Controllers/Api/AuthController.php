<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // We still use the web auth guard for logging in with passwords, I forgot
        // about this too and tried to use api/passport for everything        
        if (!auth('web')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials. Check your email or password'], 401);
        }

        $tokenName = config('auth_custom.token_name');
        $cookieName = config('auth_custom.cookie_name');
        $cookieExpr = config('auth_custom.cookie_expiry_min');

        $user = auth('web')->user();
        // Creates a warning, but this should be in the HasApiTokens trait
        $accessToken = $user->createToken($tokenName)->accessToken;

        $response = new Response('Set Cookie');
        $response->withCookie(cookie($cookieName, $accessToken['token'], $cookieExpr));
        $response->withCookie(cookie('Authorization', "Bearer {$accessToken['token']}", $cookieExpr));

        // Cookie::queue($cookieName, $accessToken['token'], $cookieExpr);
        // Cookie::queue('Authorization', "Bearer {$accessToken['token']}", $cookieExpr);

        // return ['token' => $accessToken['token']];
        return $response;
    }

    public function me(Request $request)
    {
        # TODO: this should be a middleware and we're doing the token searching manually for now.
        # For some reason, the token is not transformed into ey... jwt format automatically.
        # Before we update further, let's do it manually for now
        $cookieName = config('auth_custom.cookie_name');
        $tokenStr = $request->cookie($cookieName);
        // $user = User::byToken($tokenStr)->firstOrFail();

        $user = User::whereRelation('personalAccessToken', 'token', $tokenStr)->firstOrFail();

        Log::info($tokenStr);
        Log::info($user);

        return $user;
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return ['message' => 'You have been successfully logged out!'];
    }
}
