<?php

//modelo del usuario
class ComprasModel extends Query
{

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }

    public function getClientes()
    {
        //hacemos la consulta
        $sql = "SELECT p.cedula, p.nombres, p.apellidos, c.id FROM clientes c INNER JOIN personas p ON p.id = c.id_persona WHERE c.estado = 1 ";
        $data = $this->selectAll($sql);
        return $data;
    }
    
    public function getTasa(){
        // Hacemos la consulta
        $sql = "SELECT * FROM tasas WHERE id = (SELECT MAX(id) FROM tasas)";
        $data = $this->select($sql);
        return $data;
    }

    //buscamos el codigo del producto seleccionado
    public function getProCod()
    {
        //hacemos la consulta
        $sql = "SELECT * FROM productos WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }
    //buscamos el codigo del producto seleccionado
    public function getProCod1($cod)
    {
        //hacemos la consulta
        $sql = "SELECT * FROM productos WHERE codigo = '$cod' and estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    //buscamos los datos del producto
    public function getProductos(int $id)
    {
        //traemos todos los datos del producto seleccionado
        $sql = "SELECT * FROM productos WHERE id = $id and estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    //registrmos los detalles para mostra en la tabla (el carrito de la compra)
    public function registrarDetalle(string $table, int $id_producto, int $id_usuario, string $precio, int $cantidad, string $sub_total)
    {

        $sql = "INSERT INTO $table(id_producto, id_usuario, precio, cantidad, sub_total) VALUES (?,?,?,?,?)";
        $datos = array($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    //listamos todos los productos de la tabla detalle(para hacer el carrito de la compra)
    public function getDetalle(string $table, int $id)
    {
        $sql = "SELECT d.*, p.id AS id_pro, p.nombre, p.descripcion FROM $table d  INNER JOIN productos p ON d.id_producto = p.id  WHERE d.id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    //calculamos la suma de los sub totales para sacar el total de la compra
    public function calcularCompra(string $table, int $id_usuario)
    {
        //hacemos la suma y validamos con el id de la sesion del usuario
        $sql = "SELECT  d.sub_total, cat.iva  FROM $table d INNER JOIN productos p ON p.id = d.id_producto  INNER JOIN categorias cat ON p.id_categoria = cat.id WHERE d.id_usuario = $id_usuario ";
        $data = $this->selectAll($sql);

        $inmpuesto_iva = 0.16; // 16% expresado como decimal
        $subtotal_exento = 0;
        $subtotal_aplica = 0;

        foreach ($data as $row) {
            if ($row['iva'] === 'Exento') {
                $subtotal_exento += $row['sub_total'];
            } elseif ($row['iva'] === 'Aplica') {
                $subtotal_aplica += $row['sub_total'];
            }
        }
        $iva = ($subtotal_aplica * $inmpuesto_iva);
        $total = $subtotal_exento + ($subtotal_aplica + $iva);

        return $total;
    }

    //eliminamos el producto de la tabla detalle
    public function deleteDetalle(string $table, int $id)
    {
        //hacemos un delete (definitivo) por el id
        $sql = "DELETE FROM $table WHERE id = ?";
        $datos = array($id);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    //consultamos para ver si el producto existe la tabla detalle
    public function consultarDetalle(string $table, int $id_producto, int $id_usuario)
    {
        //acemos la consulta de validacion
        $sql = "SELECT * FROM $table WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    //actualizamos el el producto en caso de que ya exista y se quiera ingresar una mayor cantidad
    public function actualizarDetalle(string $table, string $precio, int $cantidad, string $sub_total, int $id_producto, int $id_usuario)
    {
        //actualizamos principalmente la cantidad y el subtotal
        $sql = "UPDATE $table SET precio = ?, cantidad = ?, sub_total = ? WHERE id_producto = ? AND id_usuario = ?";
        $datos = array($precio, $cantidad, $sub_total, $id_producto, $id_usuario);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    //ragistramos el total de la compra (de todos los productos de la tabla detalle(carrito de compra))
    public function registrarCompra(string $total)
    {
        //hacemos la consulta y registramos solo el total(en esta tabla)
        $sql = "INSERT INTO compras (total,fecha, hora) VALUES (?, CURRENT_DATE, CURRENT_TIME)";       
        $datos = array($total);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    //obtenemos el ultimo id de la tabla compras para registrar el detalle de la compra
    public function getId(string $table)
    {
        //hacemos la consulta gracias al id auto-incremental
        $sql = "SELECT MAX(id) AS id FROM $table";
        $data = $this->select($sql);
        return $data;
    }

    //registramos los detalles de la compra 
    public function registrarDetalleCompra(int $id_compra, int $id_producto, int $cantidad, string $precio, string $sub_total)
    {
        //hacemos la consulta para guardar los datos detalladamente
        $sql = "INSERT INTO detalle_compras (id_compra, id_producto, cantidad, precio, sub_total) VALUES (?,?,?,?,?)";
        $datos = array($id_compra, $id_producto, $cantidad, $precio, $sub_total);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function vaciarDetalle(string $table, int $id_usuario)
    {
        //hacemos la consulta para guardar los datos detalladamente
        $sql = "DELETE FROM $table WHERE id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getEmpresa()
    {

        $sql = "SELECT * FROM empresa";
        $data = $this->select($sql);
        return $data;
    }

    public function getProCompra(int $id_compra)
    {
        $sql = "SELECT c.*, d.*, p.id, p.nombre, p.descripcion, cat.iva AS iva FROM compras c INNER JOIN detalle_compras d ON c.id = d.id_compra  INNER JOIN productos p ON p.id = d.id_producto  INNER JOIN categorias cat ON p.id_categoria = cat.id WHERE c.id = $id_compra";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getHistorialCompras()
    {
        $sql = "SELECT *, CONCAT(fecha, ' ',hora) AS fyh_reg FROM compras";
        $data = $this->selectAll($sql);
        return $data;
    }

    //registramos los detalles de la compra 
    public function actualizarStock(int $stock, int $id_producto)
    {

        //hacemos la consulta para guardar los datos detalladamente
        $sql = "UPDATE productos SET cantidad = ? WHERE id = ? ";
        $datos = array($stock, $id_producto);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $data;
    }

    public function getAnularCompra( int $id_compra)
    {
        $sql = "SELECT c.*, d.* FROM compras c INNER JOIN detalle_compras d ON c.id = d.id_compra WHERE c.id = $id_compra";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getAnular(string $table, int $id_compra)
    {
        $sql = "UPDATE $table SET estado = ? WHERE id = ? ";
        $datos = array(0,$id_compra);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    //****************FIN DE LAS FUNCIONES DE LA COMPRA */
    //DE IGUAL FORMA AMBOS COMPARTEN METODOS
    //EN EL CONTROLADOR Y AQUI SOBRE TODO
    //****************FUNCIONES DE LA VENTA */

    //ragistramos el total de la compra (de todos los productos de la tabla detalle(carrito de compra))
    public function registrarVenta(int $id_cliente, string $total)
    {
        //hacemos la consulta y registramos solo el total(en esta tabla)
        $sql = "INSERT INTO ventas (id_cliente, total,fecha, hora) VALUES (?,?, CURRENT_DATE, CURRENT_TIME)";       
        $datos = array($id_cliente, $total);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    //registramos los detalles de la compra 
    public function registrarDetalleVenta(int $id_venta, int $id_producto, int $cantidad, string $precio, string $sub_total)
    {
        //hacemos la consulta para guardar los datos detalladamente
        $sql = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio, sub_total) VALUES (?,?,?,?,?)";
        $datos = array($id_venta, $id_producto, $cantidad, $precio, $sub_total);
        $data = $this->save($sql, $datos);

        //validacion de lo que devuelve save()
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getProVenta(int $id_venta)
    {
        $sql = "SELECT v.*, d.*, p.id, p.nombre, p.descripcion, cat.iva AS iva FROM ventas v INNER JOIN detalle_ventas d ON v.id = d.id_venta  INNER JOIN productos p ON p.id = d.id_producto  INNER JOIN categorias cat ON p.id_categoria = cat.id WHERE v.id = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getClientesVenta($id_venta)
    {
        $sql = "SELECT v.id, v.id_cliente, p.* FROM ventas v INNER JOIN clientes c ON c.id = v.id_cliente INNER JOIN personas p ON p.id = c.id_persona WHERE v.id = $id_venta";
        $data = $this->select($sql);
        return $data;
    }

    public function getHistorialVentas()
    {
        $sql = "SELECT c.id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo, v.*, CONCAT(v.fecha, ' ',v.hora) AS fyh_reg FROM ventas v INNER JOIN clientes c  ON c.id = v.id_cliente INNER JOIN personas p ON p.id = c.id_persona";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getAnularVenta( int $id_venta)
    {
        $sql = "SELECT v.*, d.* FROM ventas v INNER JOIN detalle_ventas d ON v.id = d.id_venta WHERE v.id = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
    }
}
