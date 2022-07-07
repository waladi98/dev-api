<?php

/* Routing untuk modul master*/
/* M : user*/
// $router->group(
//     [
//         'namespace' => 'Core',
//         'prefix' => 'master',
//         'middleware' => 'user'
//     ],
//     function() use($router)
//     {
//         $router->addRoute(
//             ['GET','POST'],
//             'guru/list','CrudController@getData'
//         );
//         $router->addRoute(
//             ['POST'],
//             'guru/create','CrudController@create'
//         );
//         $router->addRoute(
//             ['POST'],
//             'guru/modify','CrudController@modify'
//         );
//         $router->addRoute(
//             ['POST'],
//             'guru/remove','CrudController@remove'
//         );
        
//     }
// );

$router->group(
    [
        'namespace' => 'Core',
        'middleware' => 'user'
    ],
    function() use($router)
    {
        $router->addRoute(
            ['GET','POST'],
            '{modul_type:[referensi,master]+}/{table_name}',
            'CrudController@getData'
        );
        $router->addRoute(
            ['GET','POST'],
            '{table_name}',
            'CrudController@getData'
        );
        $router->addRoute(
            ['POST'],
            '{modul_type:[referensi,master]+}/{table_name}/{action:[create]+}',
            'CrudController@createData'
        );
        $router->addRoute(
            ['POST'],
            '{table_name}/{action:[create]+}',
            'CrudController@createData'
        );
        $router->addRoute(
            ['PUT'],
            '{modul_type:[referensi,master]+}/{table_name}/{action:[modify]+}',
            'CrudController@modifyData'
        );
        $router->addRoute(
            ['PUT'],
            '{table_name}/{action:[modify]+}',
            'CrudController@modifyData'
        );
        $router->addRoute(
            ['DELETE'],
            '{modul_type:[referensi,master]+}/{table_name}/{action:[remove]+}',
            'CrudController@deleteData'
        );
        $router->addRoute(
            ['DELETE'],
            '{table_name}/{action:[remove]+}',
            'CrudController@deleteData'
        );
        
    }
);