<?php


class Categorias extends Controller
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
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name);
    }

    //metodo para listar usuarios
    public function listar()
    {

        $data = $this->model->getCategorias();



        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                $data[$i]['estado'] = '<span class="acciones_activo">Activo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Editar" class="boton_acciones_edit" type="button" onclick="btnEditarCat(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                <button title="Inhabilitar" class="boton_acciones_delete" type="button" onclick="btnEliminarCat(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>
    
                </div>';
            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Inactivo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Reingresar" class="boton_acciones_reingresar" type="button" onclick="btnReingrsarCat(' . $data[$i]['id'] . ');"><i class="fa-solid fa-rotate-left"></i></button>
    
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
        $iva = $_POST['iva'];
        $id = $_POST['id'];

        $last_id = $this->model->getLastId();
        $new_id = $last_id['last_id'] + 1;
        $codigo = "COD-" . str_pad($new_id, 4, "0", STR_PAD_LEFT);

        //validacion por si alteran el valor del campo iva en el index
        if ($iva != "Exento" && $iva != "Aplica") {

            $msg = array('msg' => 'El IVA fue alterado', 'icono' => 'warning');

        } else if ($id == "") {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if ( empty($nombre) || empty($codigo) || empty($iva)) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {


                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->registrarCategorias($nombre, $codigo, $iva );

                //evaluamos si el fue registrado con exito
                if ($data == "ok") {

                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La categoria ya existe', 'icono' => 'warning');

                } else {
                    $msg = array('msg' => 'Error al registrar la categoria', 'icono' => 'error');

                }
            }
        } else {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombre) || empty($codigo) || empty($iva)
            ) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {

                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->modificarCategorias( $nombre, $codigo, $iva ,$id);

                //evaluamos si fue modificado con exito
                if ($data == "modificado") {

                    $msg = "modificado";
                    
                }else if ($data == "existe") {
                    $msg = array('msg' => 'La categoria ya existe', 'icono' => 'warning');

                } else {
                    $msg = array('msg' => 'Error al modificar la categoria', 'icono' => 'error');

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

        $data = $this->model->editarCat($id);

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //funcion para Inhabilitar usuario
    public function eliminar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionCat(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Categoria inhabilitada', 'icono' => 'success');
   
        } else {
            $msg = array('msg' => 'Error al inhabilitar categoria', 'icono' => 'error');

        }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para reingresar al usuario
    public function reingresar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionCat(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Categoria reingresada con exito', 'icono' => 'success');
   
        } else {
            $msg = array('msg' => 'Error reingresar categoria', 'icono' => 'error');

        }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
