<?php

class Controller
{

    protected $views, $model;

    //contructor de cargar model
    public function __construct()
    {
        //instanciamos la clase Views del archivo views.php
        $this->views = new Views;
        $this->cargarModel();
    }

    //funcion para cargar el mdelo
    public function cargarModel()
    {

        //obtenemos el nombre de la clase de cada controlador
        //y el nombre de todos los modelos
        $model = get_class($this) . "Model";
        $ruta = "Models/" . $model . ".php";

        //validamos si exite la ruta
        if (file_exists($ruta)) {

            require_once $ruta;

            //instanciamos el modelo
            $this->model = new $model();
        }
    }
}
