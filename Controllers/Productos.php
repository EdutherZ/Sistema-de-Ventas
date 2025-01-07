<?php


class Productos extends Controller
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
        $data['categorias'] = $this->model->getCategorias();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name, $data);
      

    }

    //metodo para listar usuarios
    public function listar()
    {

        $data = $this->model->getProductos();

        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                $data[$i]['estado'] = '<span class="acciones_activo">Activo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Editar" class="boton_acciones_edit" type="button" onclick="btnEditarPro(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                <button title="Inhabilitar" class="boton_acciones_delete" type="button" onclick="btnEliminarPro(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>
    
                </div>';
            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Inactivo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Reingresar" class="boton_acciones_reingresar" type="button" onclick="btnReingrsarPro(' . $data[$i]['id'] . ');"><i class="fa-solid fa-rotate-left"></i></button>
    
                </div>';
            }
        }


        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE); //ACEPATA ACENTOS
        die();
    }



    public function registrar()
    {
        
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio_comprax = $_POST['precio_compra'];
        $precio_ventax = $_POST['precio_venta'];
        $codigo = $_POST['codigo'];
        $categoria = $_POST['categoria'];
        $id = $_POST['id'];

        $precio_compra = str_replace(',', '.', $precio_comprax);
        $precio_venta = str_replace(',', '.', $precio_ventax);

        
        if ($id == "") {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombre) || empty($descripcion) ||empty($codigo) ||
                empty($precio_compra) ||empty($precio_venta) || empty($categoria)
            ) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {


                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->registrarProductos(
                    $codigo,
                    $nombre,
                    $descripcion,
                    $precio_compra,
                    $precio_venta,
                    $categoria
                );

                //evaluamos si el fue registrado con exito
                if ($data == "ok") {

                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El producto ya existe', 'icono' => 'warning');

                } else {
                    $msg = array('msg' => 'Error al registrar el Producto', 'icono' => 'error');

                }
            }
        } else {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombre) || empty($descripcion) ||empty($codigo) ||
                empty($precio_compra) ||empty($precio_venta) || empty($categoria)
            ) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {

                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->modificarProductos(
                    $codigo,
                    $nombre,
                    $descripcion,
                    $precio_compra,
                    $precio_venta,
                    $categoria,
                    $id
                );

                //evaluamos si fue modificado con exito
                if ($data == "modificado") {

                    $msg = "modificado";
                    
                }else if ($data == "existe") {

                    $msg = array('msg' => 'El producto ya existe', 'icono' => 'warning');

                } else {
                    $msg = array('msg' => 'Error al modificar el Producto', 'icono' => 'error');

                }
            }
        }

     
        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //METODO PARA EDITAR EL USUARIO
    public function editar(int $id)
    {

        $data = $this->model->editarPro($id);

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //funcion para Inhabilitar usuario
    public function eliminar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionPro(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Producto inhabilitado', 'icono' => 'success');
   
           } else {
               $msg = array('msg' => 'Error al inhabilitar producto', 'icono' => 'error');
   
           }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para reingresar al usuario
    public function reingresar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionPro(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Producto reingresado con exito', 'icono' => 'success');
   
           } else {
               $msg = array('msg' => 'Error reingresar producto', 'icono' => 'error');
   
           }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
