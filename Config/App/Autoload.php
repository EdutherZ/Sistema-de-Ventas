<?php

//autoload para cargar automaticamente las clases
spl_autoload_register(function($class){

    //validacion para ver si existe el archivo
    if (file_exists("config/app/" . $class . ".php")) {
        require_once "config/app/" . $class . ".php";
    }

})

?>