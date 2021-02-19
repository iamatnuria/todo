<?php

    ini_set('display_errors', 'On');
    //define('APP_W',root());

    /*function root(){
        if((dirname($_SERVER['PHP_SELF']))=='/'){
            return '/';
        }
        return dirname($_SERVER['PHP_SELF']).'/';
    }*/

    require __DIR__.'/vendor/autoload.php';

    use App\App;

    $conf=App::init();
    //constantes
    define('BASE',$conf['web']);
    define('ROOT',$conf['root']);
    define('DSN',$conf['driver'].':host='.$conf['dbhost'].';dbname='.$conf['dbname']);
    define('USR',$conf['dbuser']);
    define('PWD',$conf['dbpass']);


    //1 - Crear clase App en src
    App::run();