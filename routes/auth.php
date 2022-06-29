<?php

/* Routing untuk modul klien*/

$router->group(
    [
        'namespace' => 'Auth',
        'prefix' => 'klien'
    ],
    function() use($router)
    {
        $router->post(
            'register','klienController@register'
        );
        $router->post(
            'login','klienController@login'
        );
    }
);
$router->group(
    [
        'namespace' => 'Auth',
        'prefix' => 'pengguna',
        'middleware' => 'auth'
    ],
    function() use($router)
    {
        $router->post(
            'register','PenggunaController@register'
        );
        $router->post(
            'login','PenggunaController@login'
        );
        $router->addRoute(
            ['GET','POST'],
            'list','PenggunaController@getData'
        );
    }
);