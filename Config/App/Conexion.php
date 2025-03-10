<?php

class Conexion
{
    private $conect;

    public function __construct()
    {
        //CONEXION CON LA BASE DE DATOS
        $pdo = "mysql:host=" . host . ";dbname=" . db . ";charset=" . charset . ";";

        //try permite facilitar la captura de excepciones potenciales
        try {
            $this->conect = new PDO($pdo, user, pass);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexion " . $e->getMessage();
        }
    }
    public function conect()
    {
        //retornamos la conexion
        return $this->conect;
    }

}
