<?php

class Views
{
    //funcion para mostrar la vista
    public function getView($controlador, $vista, $user_name = "", $data = "")
    {
        //le asignamos a controlador el nombre de la clase
        $controlador = get_class($controlador);

        //validacion para mostrar la vista
        if ($controlador == "Home") {
            $vista = "Views/" . $vista . ".php";
        } else {
            $vista = "Views/" . $controlador . "/" . $vista . ".php";
        }
        require $vista;
    }
}
