<?php


class Clientes extends Controller
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

        $data = $this->model->getClientes();

        

        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                $data[$i]['estado'] = '<span class="acciones_activo">Activo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Detalles" class="boton_acciones_detalles" type="button" onclick="btnDetallesCli(' . $data[$i]['id'] . ');"><i class="fas fa-eye"></i></button>
                <button title="Editar" class="boton_acciones_edit" type="button" onclick="btnEditarCli(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                <button title="Inhabilitar" class="boton_acciones_delete" type="button" onclick="btnEliminarCli(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>
    
                </div>';
            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Inactivo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
                <button title="Reingresar" class="boton_acciones_reingresar" type="button" onclick="btnReingrsarCli(' . $data[$i]['id'] . ');"><i class="fa-solid fa-rotate-left"></i></button>
    
                </div>';
            }
        }


        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE); //ACEPATA ACENTOS
        die();
    }



    public function registrar()
    {

        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $cedula = $_POST['cedula'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $id = $_POST['id'];


        if ($id == "") {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombres) || empty($apellidos) ||
                empty($cedula) || empty($email) ||
                empty($telefono) || empty($direccion)
            ) {
                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {
                // Registrar cliente
                $data = $this->model->registrarClientes(
                    $nombres,
                    $apellidos,
                    $cedula,
                    $email,
                    $telefono,
                    $direccion,
                );

                // Evaluar si el registro fue exitoso
                if ($data == "ok") {
                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El cliente ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar el cliente', 'icono' => 'warning');
                }
            }
            

        } else {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombres) || empty($apellidos) ||
                empty($cedula) || empty($email) ||
                empty($telefono) || empty($direccion)
            ) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');

            } else {

                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->modificarClientes(
                    $nombres,
                    $apellidos,
                    $cedula,
                    $email,
                    $telefono,
                    $direccion,
                    $id
                );

                //evaluamos si fue modificado con exito
                if ($data == "modificado") {

                    $msg = "modificado";
                    
                }else if ($data == "existe") {

                    $msg = array('msg' => 'El cliente ya existe', 'icono' => 'warning');

                } else {
                    $msg = array('msg' => 'Error al modificar el cliente', 'icono' => 'error');

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

        $data = $this->model->editarCli($id);

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //funcion para Inhabilitar usuario
    public function eliminar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionCli(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Cliente inhabilitado', 'icono' => 'success');
   
           } else {
               $msg = array('msg' => 'Error al inhabilitar cliente', 'icono' => 'error');
   
           }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para reingresar al usuario
    public function reingresar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionCli(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Cliente reingresado con exito', 'icono' => 'success');
   
           } else {
               $msg = array('msg' => 'Error reingresar Cliente', 'icono' => 'error');
   
           }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
