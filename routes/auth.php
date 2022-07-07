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

/* Routing untuk modul pengguna*/
/* M : auth*/
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
    }
);
/* Routing untuk modul pengguna*/
/* M : user*/
$router->group(
    [
        'namespace' => 'Auth',
        'prefix' => 'pengguna',
        'middleware' => 'user'
    ],
    function() use($router)
    {
        $router->addRoute(
            ['GET','POST'],
            'list','PenggunaController@getData'
        );
    }
);