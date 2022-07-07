<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Models\KlienPengguna;
use Carbon\Carbon;
class UserMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {
        
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user_token= $request->input('user_token');
        if (!$user_token) {
            return response()->json([
                'code' => 402,
                'message' => 'Dibutuhkan user token untuk mengakses End Point ini!. [Akun pengguna harus login]',
                "result" => [
                    "success" => false
                ]
            ], 401);
        } else {
            $result = KlienPengguna::where('token', $user_token)->first();
            if ($result) {
                $waktu = Carbon::now();
                $data = [
                    'akses_terakhir' => $waktu,
                ]; 
                //$klien = $request->session()->get('name');
                KlienPengguna::where('token', $result->token)->update($data);
                return $next($request);
            } else {
                return response()->json([
                    'code' => 403,
                    'message' => 'User token Tidak Valid!. [Token expired atau salah]',
                    "result" => [
                        "success" => false
                    ]
                ], 401);
            }
            
        }
    }
}
