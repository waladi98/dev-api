<?php

    $router->group
    ( 
        [
            'namespace' =>'File',
            'prefix'    =>'master/file'            
        ], 
        function() use($router)
        {
            $router->post
            (
                'upload', 
                'fileController@fileUpload'
            );
            $router->post
            (
                'remove', 
                'fileController@removeFile'
            );
        }
    )
?>