<?php

//modelo del usuario
class AdministracionModel extends Query
{

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }


    //metodo para traer(listar) los usuarios de la db 
    public function getEmpresa()
    {
        //seleccionamos la tabla
        $sql = "SELECT * FROM empresa";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->select($sql);

        return $data;
    }

    public function modificar(string $rif, string $nombre, string $telefono, string $direccion, string $mensaje, int $id)
    {
            $sql = "UPDATE empresa SET rif = ?, nombre = ?, telefono = ?, direccion = ?, mensaje = ? WHERE id = ?";
            
            $datos = array($rif,  $nombre,  $telefono,  $direccion,  $mensaje, $id);

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
