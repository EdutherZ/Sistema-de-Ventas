<?php

//CARGAMOS LA BASE DE DATOS?
class Query  extends Conexion
{
    private $pdo, $con, $sql, $datos;

    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }

    //funcion para buscar datos para el inicio de sesion?
    public function select(string $sql)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        $data = $resul->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    //funcion para buscar todos los usuarios de la db
    public function selectAll(string $sql)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    //funcion para registrar todo los datos de todos los modulos
    public function save(string $sql, array $datos)
    {
        //preparamos y ejecutamos la insercion
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);

        //validacion para saber si la insercion fue exitosa
        if ($data) {

            $res = 1;
        }else {

            $res = 0;
        }
        return $res;
    }

    //metodo para obtener el ultimo id insertado
    public function getLastInserId(){

        return $this->con->lastInsertId();
    }
}
