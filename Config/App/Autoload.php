<?php

//autoload para cargar automaticamente las clases
spl_autoload_register(function($class){

    //validacion para ver si existe el archivo
    if (file_exists("Config/App/" . $class . ".php")) {
        require_once "Config/App/" . $class . ".php";
    }

})

?>