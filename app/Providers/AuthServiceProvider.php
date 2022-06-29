<?php

namespace App\Providers;

use App\Models\Klien;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('token')) {
                $result = Klien::where('token', $request->input('token'))->first();
                if ($result) {
                    $waktu = Carbon::now();
                    $data = [
                        'akses_terakhir' => $waktu,
                    ]; 
                    //$klien = $request->session()->get('name');
                    Klien::where('token', $result->token)->update($data);
                }
                return $result;
            }
        });
        
    }
}
