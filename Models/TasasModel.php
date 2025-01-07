<?php

//modelo del usuario
class TasasModel extends Query
{
   
    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }


    //metodo para traer(listar) los usuarios de la db 
    public function getTasas()
    {
        // Hacemos la consulta
        $sql = "SELECT * FROM tasas WHERE id = (SELECT MAX(id) FROM tasas)";
        $data = $this->select($sql);
        return $data;
    }

    //metodo para traer(listar) los usuarios de la db 
    public function listarTasas()
    {
        //seleccionamos la tabla
        $sql = "SELECT * FROM tasas";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->selectAll($sql);

        return $data;
    }

    public function modificar(string $dolar, string $euro, int $id_usuario)
    {
            $sql = "INSERT INTO tasas (dolar,euro, id_usuario) VALUES (?,?,?)";
            
            $datos = array($dolar, $euro, $id_usuario);

            //lamamos al metodo save que esta en query.php
            $data = $this->save($sql, $datos);

            //validacion de lo que devuelve save()
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        

        //captutramos $res en funfiones.js
        return $res;
    }
    
}
