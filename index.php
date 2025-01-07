<?php

require_once "Config/Config.php";

// DECLARAMOS Y VALIDAMOS LA URL Y EL ARRAY QUE LA CONTENDRA
$ruta = !empty($_GET['url']) ? $_GET['url'] : "Home/index";
$array = explode("/", $ruta);
$controller = $array[0];
$metodo = "index";
$parametro = "";

//VALIDAMOS EL ARRAY QUE CONTENDRA LA URL EN LA POSICION 1
// EN LA 1 PASARA EL CONTROLADOR
if (!empty($array[1])) {
    if (!empty($array[1]) != "") {
        $metodo = $array[1];
    }
}

//VALIDAMOS EL ARRAY QUE CONTENDRA LA URL EN LA POSICION 2 Y SUPERIOR
//EN LA DOS EL METODO Y A PARTIR DE ALLI LOS PARATMETROS
if (!empty($array[2])) {
    if (!empty($array[2]) != "") {
        for ($i = 2; $i < count($array); $i++) {
            $parametro .= $array[$i] . ",";
        }
        $parametro = trim($parametro, ",");
    }
}

//llamamos el autoload
require_once "Config/App/Autoload.php";


//verificamos si el directorio de url esxite
$dirControllers = "Controllers/" . $controller . ".php";
if (file_exists($dirControllers)) {

    //LLAMAMOS AL CONTROLADOR E INSTANCIAMOS
    require_once $dirControllers;
    $controller = new $controller();

    //VALIDAMOS EL METODO
    if (method_exists($controller, $metodo)) {
        $controller->$metodo($parametro);
    } else {
        echo "No existe el metodo";
    }
} else {

    echo "No existe el Controlador";
    //require "Views/Templates/404-view.php";
}
