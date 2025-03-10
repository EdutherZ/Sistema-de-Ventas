<?php

//modelo del usuario
class ProductosModel extends Query
{
    private $nombre,   $descripcion, $precio_compra, $precio_venta, $codigo, $id, $estado, $categoria;

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }

    public function getCategorias()
    {

        $sql = "SELECT * FROM categorias WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    //metodo para traer(listar) los usuarios de la db 
    public function getProductos()
    {
        //seleccionamos la tabla
        $sql = "SELECT p.*, c.id AS id_categoria, c.nombre AS categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->selectAll($sql);

        return $data;
    }

    //funcion para registrar los datos del usuario
    public function registrarProductos(
        string $codigo,
        string $nombre,
        string $descripcion,
        string $precio_compra,
        string $precio_venta,
        string $categoria,

    ) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio_compra = $precio_compra;
        $this->precio_venta = $precio_venta;
        $this->categoria = $categoria;


        //consulta para verificar si el usuario ya existe
        $verificar = "SELECT * FROM productos WHERE codigo = '$this->codigo'";
        $existe = $this->select($verificar);

        //validacion respectiva para ver si existe
        if (empty($existe)) {

            $sql = "INSERT INTO productos(codigo, nombre, descripcion, precio_compra, precio_venta, id_categoria) VALUES (?, ?, ?, ?, ?, ?)";
            $datos = array(
                $this->codigo,
                $this->nombre,
                $this->descripcion,
                $this->precio_compra,
                $this->precio_venta,
                $this->categoria,
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
    public function modificarProductos(
        string $codigo,
        string $nombre,
        string $descripcion,
        string $precio_compra,
        string $precio_venta,
        string $categoria,
        int $id

    ) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio_compra = $precio_compra;
        $this->precio_venta = $precio_venta;
        $this->categoria = $categoria;
        $this->id = $id;


        //consulta para verificar si el usuario ya existe
        $verificar = "SELECT codigo FROM productos where id != '$this->id'";
        $existe = $this->selectAll($verificar);

        $count = 0;
        foreach ($existe as $row) {

            if ($row['codigo'] === $this->codigo) {

                $count++;
            }
        }
        if ($count == 0) {

            $sql = "UPDATE productos SET  codigo = ?,nombre = ?,descripcion = ?,
            precio_compra = ?, precio_venta = ?, id_categoria = ? WHERE id = ?";

            $datos = array(
                $this->codigo,
                $this->nombre,
                $this->descripcion,
                $this->precio_compra,
                $this->precio_venta,
                $this->categoria,
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
    public function editarPro(int $id)
    {

        $sql = "SELECT * FROM productos where id = $id";
        $data = $this->select($sql);
        return $data;
    }

    //funcion para inhabilitar y reingresar el usuario fuaa
    public function accionPro(int $estado, int $id)
    {
        //asignamos el valor de id y estado
        $this->id = $id;
        $this->estado = $estado;
        //actualizamos el estado
        $sql = "UPDATE productos set estado = ? where id = ?";
        //almacenamos el estado y id en datos
        $datos = array($this->estado, $this->id);
        //le enviamos a query.php el sql y los datos
        $data = $this->save($sql, $datos);
        //a data se le asigna lo que devulva query.ph
        //retornamos data
        return $data;
    }
}
