<?php

//modelo del usuario
class CategoriasModel extends Query
{
    private $nombre, $iva, $codigo, $id, $estado;

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }


    //metodo para traer(listar) los usuarios de la db 
    public function getCategorias()
    {
        //seleccionamos la tabla
        $sql = "SELECT * FROM categorias";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->selectAll($sql);

        return $data;
    }

    //funcion para registrar los datos del usuario
    public function registrarCategorias(
        string $nombre,
        string $codigo,
        string $iva

    ) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->iva = $iva;


        //consulta para verificar si el usuario ya existe
        $verificar = "SELECT * FROM categorias WHERE codigo = '$this->codigo' or nombre = '$this->nombre'";
        $existe = $this->select($verificar);

        //validacion respectiva para ver si existe
        if (empty($existe)) {

            $sql = "INSERT INTO categorias (nombre,codigo,iva) VALUES (?,?,?)";
            $datos = array(
                $this->nombre,
                $this->codigo,
                $this->iva,
            );

            //lamamos al metodo save que esta en query.php
            $data = $this->save($sql, $datos);

            //validacion de lo que devuelve save()
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        //captutramos $res en funfiones.js
        return $res;
    }

    //funcion para registrar los datos del usuario
    public function modificarCategorias(
        string $nombre,
        string $codigo,
        string $iva,
        int $id
    ) {

        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->iva = $iva;
        $this->id = $id;


        //consulta para verificar si el usuario ya existe
        $verificar = "SELECT codigo, nombre FROM categorias where id != '$this->id'";
        $existe = $this->selectAll($verificar);

        $count = 0;
        foreach ($existe as $row) {

            //strcasecmp ($a,$b) detecta si el nombre es igual ignorando mayuscula o minusculas
            if ($row['codigo'] === $this->codigo || strcasecmp($row['nombre'], $this->nombre) === 0) {

                $count++;
            }
        }
        if ($count == 0) {

            $sql = "UPDATE categorias SET nombre = ?, iva = ? WHERE id = ?";

            $datos = array(
                $this->nombre,
                $this->iva,
                $this->id
            );

            //lamamos al metodo save que esta en query.php
            $data = $this->save($sql, $datos);

            //validacion de lo que devuelve save()
            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    //metodo para editar al usuario mediante su ID
    public function editarCat(int $id)
    {

        $sql = "SELECT * FROM categorias where id = $id";
        $data = $this->select($sql);
        return $data;
    }

    //funcion para inhabilitar y reingresar el usuario fuaa
    public function accionCat(int $estado, int $id)
    {
        //asignamos el valor de id y estado
        $this->id = $id;
        $this->estado = $estado;
        //actualizamos el estado
        $sql = "UPDATE categorias set estado = ? where id = ?";
        //almacenamos el estado y id en datos
        $datos = array($this->estado, $this->id);
        //le enviamos a query.php el sql y los datos
        $data = $this->save($sql, $datos);
        //a data se le asigna lo que devulva query.ph
        //retornamos data
        return $data;
    }
    
    //funcion para obtener el ultimo id 
    public function getLastId(){
        
        $sql = "SELECT MAX(id) AS last_id FROM categorias";
        $data = $this->select($sql);
        return $data;
    }
}
