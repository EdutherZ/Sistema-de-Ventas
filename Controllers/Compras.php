<?php

class Compras extends Controller
{

    public function __construct()
    {
        session_start();
        //validacion para el inicio de sesion de los usuarios activos o no
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
    }

    public function index()
    {
        $data['prod'] = $this->model->getProCod();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name, $data);
    }
    //buscamos el codigo del producto que el usuario selecciono
    public function buscarCodigo($cod)
    {
        $tasa = $this->model->getTasa();
        $data = $this->model->getProCod1($cod);
        $data['precio_venta_bs'] = $data['precio_venta'] * $tasa['dolar'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //ingresar datos
    public function ingresar()
    {
        //buscamos los datos del producto
        $id = $_POST['id'];
        $datos = $this->model->getProductos($id);

        //almacenamos los datos necesarios del producto
        $id_producto = $datos['id'];
        $id_usuario = $_SESSION['id_usuario']; //lo extraemos de la session del usuario
        $id_producto = $datos['id'];
        $precio_compra = $datos['precio_compra'];
        $cantidad = $_POST['cantidad'];

        //verificamos si el producto ya existe en la tabla detalle (para hacer el carrito de la compra)
        $comprobar = $this->model->consultarDetalle('detalle', $id_producto, $id_usuario);

        if (empty($comprobar)) {

            //si no existe lo registramos

            $sub_total = $precio_compra * $cantidad;

            //enviamos los datos recibidos o necesarios para la tabla detalle
            $data = $this->model->registrarDetalle('detalle', $id_producto, $id_usuario, $precio_compra, $cantidad, $sub_total);

            if ($data == "ok") {
                $msg = "ok";
            } else {
                $msg = array('msg' => 'Error al ingresar el producto', 'icono' => 'error');
            }
        } else {

            //si ya existe, solo actualizamos la cantidad y el sub_total

            $total_cantidad = $comprobar['cantidad'] + $cantidad;
            $sub_total = $total_cantidad * $precio_compra;

            //enviamos los datos recibidos o necesarios para la tabla detalle
            $data = $this->model->actualizarDetalle('detalle', $precio_compra, $total_cantidad, $sub_total, $id_producto, $id_usuario);

            if ($data == "modificado") {
                $msg = "modificado";
            } else {
                $msg = array('msg' => 'Error al modificar el producto', 'icono' => 'error');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //listamos todos los productos de la tabla detalle(formamos asi el carrito de la compra)
    public function listar($table)
    {
        $tasa = $this->model->getTasa();
        $id_usuario = $_SESSION['id_usuario'];
        $data['detalle'] = $this->model->getDetalle($table, $id_usuario); //extraemos todos los datos del producto para formar la tabla
        $data['total_pagar'] = $this->model->calcularCompra($table, $id_usuario); //calculamos el total de la compra
        $data['total_pagar_bs'] = $data['total_pagar'] * $tasa['dolar'];
        /* Iteramos sobre cada elemento en 'detalle' y añadimos el nuevo campo
        El símbolo & antes de $detalle indica que $detalle es una referencia al elemento actual del array. 
        Esto significa que cualquier cambio hecho a $detalle se reflejará directamente en el array original $data['detalle']*/
        foreach ($data['detalle'] as &$detalle) {
            $detalle['sub_total_bs'] = $detalle['sub_total'] * $tasa['dolar'];
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //eliminamos el producto de la cola(del carrito de la compra)
    public function delete($id)
    {
        //llamamos al metodo en el modelo
        $data = $this->model->deleteDetalle('detalle', $id);

        if ($data == "ok") {
            $msg = "ok";
        } else {
            $msg = array('msg' => 'Error al eliminar detalle', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //registramos la compra de todo los productos seleccionados 
    //(todos los que estan en la tabla detalle(el carrito de compra))
    //esta es la compra final por asi decirlo
    public function registrarCompra()
    {

        $id_usuario = $_SESSION['id_usuario'];
        $total = $this->model->calcularCompra('detalle', $id_usuario); //obtenemos el total de la compra
        $data = $this->model->registrarCompra($total); //registramos el total de la compra final

        $id_compra = $this->model->getId('compras'); //obtenemos el ultimo id para asignarlo al detalle de la compra

        if ($data == "ok") {

            $detalle = $this->model->getDetalle('detalle', $id_usuario); //obtenemos los datos detallados dede todo los productos del carrito(tabla detalle)

            //recorremos los datos y los asignamos a variables
            foreach ($detalle as $row) {
                $cantidad = $row['cantidad'];
                $precio_compra = $row['precio'];
                $sub_total = $cantidad * $precio_compra;
                $id_producto = $row['id_producto'];

                //registramos los detalles de la compra realizada
                $this->model->registrarDetalleCompra($id_compra['id'], $id_producto, $cantidad, $precio_compra, $sub_total);

                $stock_actual = $this->model->getProductos($id_producto);

                $stock = $stock_actual['cantidad'] + $cantidad;

                $this->model->actualizarStock($stock, $id_producto);
            }

            $vaciar = $this->model->vaciarDetalle('detalle', $id_usuario);

            if ($vaciar == "ok") {
                $msg = array('msg' => 'ok', 'id_compra' => $id_compra['id']);
            } else {
                $msg = "Error al eliminar detalle";
            }
        } else {
            $msg = "Error al registrar Ingreso";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para generar la factura
    public function generarPdf($id_compra)
    {
        $datos_emp = $this->model->getEmpresa();
        $datos_prod = $this->model->getProCompra($id_compra);

        $empresa = mb_convert_encoding($datos_emp, 'ISO-8859-1', 'UTF-8');
        $productos = mb_convert_encoding($datos_prod, 'ISO-8859-1', 'UTF-8');

        require('Libraries/fpdf/fpdf.php');


        $pdf = new FPDF('P', 'mm', array(80, 200));
        $pdf->AddPage();
        $pdf->SetMargins(2, 0, 0);
        $pdf->SetTitle('Reporte Compra');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 5, 'RIF: ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(22, 5, 'J-' . $empresa['rif'], 0, 1, 'R');


        $pdf->SetFont('Arial', 'B', 11);
        // Define el ancho máximo para la celda
        $anchoMaximo = 58;

        // Obtén el ancho del texto
        $anchoTexto = $pdf->GetStringWidth($empresa['nombre']);

        // Calcula la posición x para centrar el texto
        $x = ($pdf->GetPageWidth() - ($anchoTexto + 5)) / 2;

        // Si el ancho del texto supera el ancho máximo, ajusta la celda
        if ($anchoTexto > $anchoMaximo) {
            $pdf->Cell(9, 5, ' ', 0, 0, 'L');
            $pdf->MultiCell($anchoMaximo, 5, $empresa['nombre'], 0, 'C');
        } else {
            $pdf->SetX($x);
            $pdf->MultiCell(55, 5, $empresa['nombre'], 0, 'L');
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, 'Telefono: ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['telefono'], 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(45, 5, 'Direccion:', 0, 1, 'R');
        $pdf->SetFont('Arial', '', 9);

        // Obtén el ancho del texto
        $anchoTexto = $pdf->GetStringWidth($empresa['direccion']);
        // Calcula la posición x para centrar el texto
        $x2 = ($pdf->GetPageWidth() - ($anchoTexto + 5)) / 2;
        // Si el ancho del texto supera el ancho máximo, ajusta la celda
        if ($anchoTexto > $anchoMaximo) {
            $pdf->Cell(8, 5, ' ', 0, 0, 'L');
            $pdf->MultiCell($anchoMaximo - 3, 4, $empresa['direccion'], 0, 'C');
        } else {
            $pdf->SetX($x2);
            $pdf->MultiCell(35, 4, $empresa['direccion'], 0, 'L');
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(21, 5, mb_convert_encoding('N° Compra: ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, str_pad($id_compra, 8, "0", STR_PAD_LEFT), 0, 1, 'L');
        $pdf->Ln();


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 5, 'Control de Pago', 0, 1, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, 'Fecha:  ' . $productos[0]['fecha'], 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(42, 5, 'Hora:  ' . $productos[0]['hora'], 0, 1, 'R');

        //encabezado de productos
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(9, 5, 'Cant', 0, 0, 'L', true);
        $pdf->Cell(33, 5, 'Nombre', 0, 0, 'L', true);
        $pdf->Cell(14, 5, 'Precio', 0, 0, 'L', true);
        $pdf->Cell(20, 5, 'Sub Total', 0, 1, 'L', true);
        //$total = 0.00;
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', '', 9);

        foreach ($productos as $row) {
            $pdf->Cell(9, 5, $row['cantidad'], 0, 0, 'L');

            // Calcula el ancho disponible para el nombre y la descripción
            $ancho_disponible = 30; // Ancho total disponible para nombre + descripción
            $ancho_nombre = strlen($row['nombre']) + 5;
            $ancho_descripcion = strlen($row['descripcion']) + 6;

            // Si el ancho total excede el espacio disponible, muestra solo la descripción debajo del nombre
            if ($ancho_nombre + $ancho_descripcion > $ancho_disponible) {
                $pdf->Cell($ancho_nombre - 2, 5, $row['nombre'], 0, 0, 'L');
                $pdf->Cell(23, 5, ' ', 0, 0, 'L'); // Espacio en blanco
                $pdf->Cell(14, 5, number_format($row['precio'], 2, ',', '.'), 0, 0, 'L');
                $pdf->Cell(17, 5, number_format($row['sub_total'], 2, ',', '.'), 0, 1, 'L');
                $pdf->Cell(9, 5, ' ', 0, 0, 'L');
                $pdf->Cell($ancho_disponible, 5, $row['descripcion'], 0, 1, 'L'); // Muestra la descripción
            } else {
                $pdf->Cell(33, 5, $row['nombre'] . '  ' . $row['descripcion'], 0, 0, 'L');
                $pdf->Cell(14, 5, number_format($row['precio'], 2, ',', '.'), 0, 0, 'L');
                $pdf->Cell(17, 5, number_format($row['sub_total'], 2, ',', '.'), 0, 1, 'L');
            }
        }

        $pdf->Ln();
        for ($i = 0; $i < 37; $i++) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(2, 5, '-', 0, 0, '');
        }
        $pdf->Ln();

        $inmpuesto_iva = 0.16; // 16% expresado como decimal
        $subtotal_exento = 0;
        $subtotal_aplica = 0;

        foreach ($productos as $producto) {
            if ($producto['iva'] === 'Exento') {
                $subtotal_exento += $producto['sub_total'];
            } elseif ($producto['iva'] === 'Aplica') {
                $subtotal_aplica += $producto['sub_total'];
            }
        }
        $iva = $subtotal_aplica * $inmpuesto_iva;

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 5, 'EXENTO: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, 'Bs ' . number_format($subtotal_exento, 2, ',', '.'), 0, 1, 'L');


        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(5, 5, 'BI: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, 'Bs ' . number_format($subtotal_aplica, 2, ',', '.') . '  IVA 16,00%', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Bs ' . number_format($iva, 2, ',', '.'), 0, 1, 'R');

        for ($i = 0; $i < 37; $i++) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(2, 5, '-', 0, 0, '');
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(50, 5, 'Total', 0, 0, 'L');
        $pdf->Cell(23, 5, 'Bs ' . number_format($productos[0]['total'], 2, ',', '.'), 0, 1, 'R');

        $pdf->Output();
    }

    public function historial()
    {
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "historial", $user_name);
    }
    public function listar_historial()
    {
        $data = $this->model->getHistorialCompras();
        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                $data[$i]['estado'] = '<span class="acciones_activo">Completado</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button  class="boton_acciones_detalles" title="Anular" onclick="btnAnularC(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>

                <a class="boton_acciones_reporte" title="PDF" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blank"><i class="fa-regular fa-file-pdf"></i></a>
                </div>';
            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Anulado</span>';
                $data[$i]['acciones'] = '<div class="acciones_crud">

                <a class="boton_acciones_reporte" title="PDF" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blank"><i class="fa-regular fa-file-pdf"></i></a>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularCompra($id_compra)
    {
        $data = $this->model->getAnularCompra($id_compra);
        $anular = $this->model->getAnular('compras', $id_compra);
        foreach ($data as $row) {

            $stock_actual = $this->model->getProductos($row['id_producto']);

            $stock = $stock_actual['cantidad'] - $row['cantidad'];

            $this->model->actualizarStock($stock, $row['id_producto']);
        }
        if ($anular === "ok") {
            $msg = array('msg' => 'Ingreso anulado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al anular', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //*************************************************************************** */

    //****************FIN DE LAS FUNCIONES DE LA COMPRA */

    //DE IGUAL FORMA AMBOS COMPARTEN METODOS, AQUI Y SOBRE TODO EN EL MODELO

    //****************FUNCIONES DE LA VENTA */

    //**************************************************************************** */


    public function ventas()
    {
        $data['clientes'] = $this->model->getClientes();
        $data['prod'] = $this->model->getProCod();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "ventas", $user_name, $data);
    }

    //ingresar datos venta
    public function ingresarVenta()
    {
        //buscamos los datos del producto
        $id = $_POST['id'];
        $datos = $this->model->getProductos($id);

        //almacenamos los datos necesarios del producto
        $id_producto = $datos['id'];
        $id_usuario = $_SESSION['id_usuario']; //lo extraemos de la session del usuario
        $id_producto = $datos['id'];
        $precio_venta = $datos['precio_venta'];
        $cantidad = $_POST['cantidad'];

        //verificamos si el producto ya existe en la tabla detalle (para hacer el carrito de la compra)
        $comprobar = $this->model->consultarDetalle('detalle_temp', $id_producto, $id_usuario);

        if (empty($comprobar)) {

            //si no existe lo registramos

            $sub_total = $precio_venta * $cantidad;

            //enviamos los datos recibidos o necesarios para la tabla detalle
            $data = $this->model->registrarDetalle('detalle_temp', $id_producto, $id_usuario, $precio_venta, $cantidad, $sub_total);

            if ($data == "ok") {
                $msg = "ok";
            } else {
                $msg = array('msg' => 'Error al ingresar el producto', 'icono' => 'error');
            }
        } else {

            //si ya existe, solo actualizamos la cantidad y el sub_total

            $total_cantidad = $comprobar['cantidad'] + $cantidad;
            $sub_total = $total_cantidad * $precio_venta;

            //enviamos los datos recibidos o necesarios para la tabla detalle
            $data = $this->model->actualizarDetalle('detalle_temp', $precio_venta, $total_cantidad, $sub_total, $id_producto, $id_usuario);

            if ($data == "modificado") {
                $msg = "modificado";
            } else {
                $msg = array('msg' => 'Error al modificar el producto', 'icono' => 'error');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //eliminamos el producto de la cola(del carrito de la compra)
    public function deleteVenta($id)
    {
        //llamamos al metodo en el modelo
        $data = $this->model->deleteDetalle('detalle_temp', $id);

        if ($data == "ok") {
            $msg = "ok";
        } else {
            $msg = array('msg' => 'Error al eliminar detalle', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //registramos la compra de todo los productos seleccionados 
    //(todos los que estan en la tabla detalle(el carrito de compra))
    //esta es la compra final por asi decirlo
    public function registrarVenta($id_cliente)
    {

        $id_usuario = $_SESSION['id_usuario'];
        $tasa = $this->model->getTasa();
        $total = $this->model->calcularCompra('detalle_temp', $id_usuario); //obtenemos el total de la compra
        $total = $total * $tasa['dolar'];
        $data = $this->model->registrarVenta($id_cliente, $total); //registramos el total de la compra final

        $id_venta = $this->model->getId('ventas'); //obtenemos el ultimo id para asignarlo al detalle de la compra

        if ($data == "ok") {

            //obtenemos los datos detallados dede todo los productos del carrito(tabla detalle)
            $detalle = $this->model->getDetalle('detalle_temp', $id_usuario); 

            //recorremos los datos y los asignamos a variables
            foreach ($detalle as $row) {
                $cantidad = $row['cantidad'];
                $precio_compra = $row['precio'] * $tasa['dolar'];
                $sub_total = $cantidad * $precio_compra;
                $id_producto = $row['id_producto'];

                //registramos los detalles de la compra realizada
                $this->model->registrarDetalleVenta($id_venta['id'], $id_producto, $cantidad, $precio_compra, $sub_total);

                $stock_actual = $this->model->getProductos($id_producto);

                $stock = $stock_actual['cantidad'] - $cantidad;

                $this->model->actualizarStock($stock, $id_producto);
            }

            $vaciar = $this->model->vaciarDetalle('detalle_temp', $id_usuario);

            if ($vaciar == "ok") {
                $msg = array('msg' => 'ok', 'id_venta' => $id_venta['id']);
            } else {
                $msg = "Error al eliminar detalle";
            }
        } else {
            $msg = "Error al realizar la venta";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para generar la factura
    public function generarPdfVenta($id_venta)
    {
        $datos_emp = $this->model->getEmpresa();
        $datos_prod = $this->model->getProVenta($id_venta);
        $datos_cli = $this->model->getClientesVenta($id_venta);

        $empresa = mb_convert_encoding($datos_emp, 'ISO-8859-1', 'UTF-8');
        $productos = mb_convert_encoding($datos_prod, 'ISO-8859-1', 'UTF-8');
        $clientes = mb_convert_encoding($datos_cli, 'ISO-8859-1', 'UTF-8');

        require('Libraries/fpdf/fpdf.php');


        $pdf = new FPDF('P', 'mm', array(80, 200));
        $pdf->AddPage();
        $pdf->SetMargins(2, 0, 0);
        $pdf->SetTitle('Reporte Venta');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, 'RIF: ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 5, 'J-' . $empresa['rif'], 0, 1, 'R');


        $pdf->SetFont('Arial', 'B', 10);
        // Define el ancho máximo para la celda
        $anchoMaximo = 58;

        // Obtén el ancho del texto
        $anchoTexto = $pdf->GetStringWidth($empresa['nombre']);

        // Calcula la posición x para centrar el texto
        $x = ($pdf->GetPageWidth() - ($anchoTexto + 5)) / 2;

        // Si el ancho del texto supera el ancho máximo, ajusta la celda
        if ($anchoTexto > $anchoMaximo) {
            $pdf->Cell(9, 5, ' ', 0, 0, 'L');
            $pdf->MultiCell($anchoMaximo, 5, $empresa['nombre'], 0, 'C');
        } else {
            $pdf->SetX($x);
            $pdf->MultiCell(55, 5, $empresa['nombre'], 0, 'L');
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, 'Telefono: ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['telefono'], 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(45, 5, 'Direccion:', 0, 1, 'R');
        $pdf->SetFont('Arial', '', 9);

        // Obtén el ancho del texto
        $anchoTexto = $pdf->GetStringWidth($empresa['direccion']);
        // Calcula la posición x para centrar el texto
        $x2 = ($pdf->GetPageWidth() - ($anchoTexto + 5)) / 2;
        // Si el ancho del texto supera el ancho máximo, ajusta la celda
        if ($anchoTexto > $anchoMaximo) {
            $pdf->Cell(8, 5, ' ', 0, 0, 'L');
            $pdf->MultiCell($anchoMaximo - 3, 4, $empresa['direccion'], 0, 'C');
        } else {
            $pdf->SetX($x2);
            $pdf->MultiCell(35, 4, $empresa['direccion'], 0, 'L');
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(17, 5, mb_convert_encoding('N° Venta', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, str_pad($id_venta, 8, "0", STR_PAD_LEFT), 0, 1, 'L');


        //encabezado Clientes
        //
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(13, 5, 'Cliente: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $clientes['nombres'] . ' ' . $clientes['apellidos'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(13, 5, 'RIF/C.I: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, 'V-' . $clientes['cedula'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(15, 5, 'Telefono: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $clientes['telefono'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(16, 5, 'Direccion: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(60, 5, $clientes['direccion'], 0, 'L');

        //no uso salto de linea le doy mas espacio al alto de letra
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(70, 8, 'Control de Pago', 0, 1, 'C');


        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 5, 'Fecha:  ' . $productos[0]['fecha'], 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(42, 5, 'Hora:  ' . $productos[0]['hora'], 0, 1, 'R');

        //encabezado de productos
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(9, 5, 'Cant', 0, 0, 'L', true);
        $pdf->Cell(33, 5, 'Nombre', 0, 0, 'L', true);
        $pdf->Cell(14, 5, 'Precio', 0, 0, 'L', true);
        $pdf->Cell(20, 5, 'Sub Total', 0, 1, 'L', true);
        //$total = 0.00;
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', '', 9);

        foreach ($productos as $row) {
            $pdf->Cell(9, 5, $row['cantidad'], 0, 0, 'L');

            // Calcula el ancho disponible para el nombre y la descripción
            $ancho_disponible = 30; // Ancho total disponible para nombre + descripción
            $ancho_nombre = strlen($row['nombre']) + 5;
            $ancho_descripcion = strlen($row['descripcion']) + 6;

            // Si el ancho total excede el espacio disponible, muestra solo la descripción debajo del nombre
            if ($ancho_nombre + $ancho_descripcion > $ancho_disponible) {
                $pdf->Cell($ancho_nombre - 2, 5, $row['nombre'], 0, 0, 'L');
                $pdf->Cell(23, 5, ' ', 0, 0, 'L'); // Espacio en blanco
                $pdf->Cell(14, 5, number_format($row['precio'], 2, ',', '.'), 0, 0, 'L');
                $pdf->Cell(17, 5, number_format($row['sub_total'], 2, ',', '.'), 0, 1, 'L');
                $pdf->Cell(9, 5, ' ', 0, 0, 'L');
                $pdf->Cell($ancho_disponible, 5, $row['descripcion'], 0, 1, 'L'); // Muestra la descripción
            } else {
                $pdf->Cell(33, 5, $row['nombre'] . '  ' . $row['descripcion'], 0, 0, 'L');
                $pdf->Cell(14, 5, number_format($row['precio'], 2, ',', '.'), 0, 0, 'L');
                $pdf->Cell(17, 5, number_format($row['sub_total'], 2, ',', '.'), 0, 1, 'L');
            }
        }

        $pdf->Ln();
        for ($i = 0; $i < 37; $i++) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(2, 5, '-', 0, 0, '');
        }
        $pdf->Ln();

        $inmpuesto_iva = 0.16; // 16% expresado como decimal
        $subtotal_exento = 0;
        $subtotal_aplica = 0;

        foreach ($productos as $producto) {
            if ($producto['iva'] === 'Exento') {
                $subtotal_exento += $producto['sub_total'];
            } elseif ($producto['iva'] === 'Aplica') {
                $subtotal_aplica += $producto['sub_total'];
            }
        }
        $iva = $subtotal_aplica * $inmpuesto_iva;

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 5, 'EXENTO: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, 'Bs ' . number_format($subtotal_exento, 2, ',', '.'), 0, 1, 'L');


        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(5, 5, 'BI: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, 'Bs ' . number_format($subtotal_aplica, 2, ',', '.') . '  IVA 16,00%', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Bs ' . number_format($iva, 2, ',', '.'), 0, 1, 'R');

        for ($i = 0; $i < 37; $i++) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(2, 5, '-', 0, 0, '');
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(50, 5, 'Total', 0, 0, 'L');
        $pdf->Cell(23, 5, 'Bs ' . number_format($productos[0]['total'], 2, ',', '.'), 0, 1, 'R');

        $pdf->Output();
    }

    public function historial_ventas()
    {
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "historial_ventas", $user_name);
    }

    public function listar_historial_ventas()
    {
        $data = $this->model->getHistorialVentas();
        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                $data[$i]['estado'] = '<span class="acciones_activo">Completada</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button  class="boton_acciones_detalles" title="Anular" onclick="btnAnularV(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>

                <a class="boton_acciones_reporte" title="PDF" href="' . base_url . "Compras/generarPdfVenta/" . $data[$i]['id'] . '" target="_blank"><i class="fa-regular fa-file-pdf"></i></a>
                </div>';
            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Anulada</span>';
                $data[$i]['acciones'] = '<div class="acciones_crud">

                <a class="boton_acciones_reporte" title="PDF" href="' . base_url . "Compras/generarPdfVenta/" . $data[$i]['id'] . '" target="_blank"><i class="fa-regular fa-file-pdf"></i></a>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularVenta($id_venta)
    {
        $data = $this->model->getAnularVenta($id_venta);
        $anular = $this->model->getAnular('ventas', $id_venta);
        foreach ($data as $row) {

            $stock_actual = $this->model->getProductos($row['id_producto']);

            $stock = $stock_actual['cantidad'] + $row['cantidad'];

            $this->model->actualizarStock($stock, $row['id_producto']);
        }
        if ($anular === "ok") {
            $msg = array('msg' => 'Venta anulada', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al anular', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
