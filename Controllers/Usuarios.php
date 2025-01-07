<?php


class Usuarios extends Controller
{
    public function __construct()
    {
        session_start();
        //PODRIA ESTAR EN EL METODO INDEX PERO AHORA VALIDAR ESTA EN USUARIOS Y ASI NO DA ERROR
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

        $data = $this->model->getUsuarios();

        //mostramos las acciones por usuario con un for
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['estado'] == 1) {

                if ($data[$i]['id'] === $data[$i]['min_id']) {

                    $data[$i]['estado'] = '<span class="acciones_activo">Activo</span>';

                    $data[$i]['acciones'] = '<div class="acciones_crud">
                    
                   <span>Administrador</span>
                   
                    </div>';
                    
                }else{

                    $data[$i]['estado'] = '<span class="acciones_activo">Activo</span>';

                    $data[$i]['acciones'] = '<div class="acciones_crud">
                    
                    <a class="boton_acciones_permisos" title="Permisos" href="' . base_url . "Usuarios/permisos/" . $data[$i]['id'] .'"><i class="fas fa-key"></i></a>
                    
                    <button title="Editar" class="boton_acciones_edit" type="button" onclick="btnEditarUser(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                    <button title="Inhabilitar" class="boton_acciones_delete" type="button" onclick="btnEliminarUser(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>
    
                    </div>';
                }

            } else {

                $data[$i]['estado'] = '<span class="acciones_inactivo">Inactivo</span>';

                $data[$i]['acciones'] = '<div class="acciones_crud">
            <button title="Reingresar" class="boton_acciones_reingresar" type="button" onclick="btnReingrsarUser(' . $data[$i]['id'] . ');"><i class="fa-solid fa-rotate-left"></i></button>

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
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        $id = $_POST['id'];
        $hash = password_hash($clave, PASSWORD_BCRYPT);

        if ($id == "") {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombres) || empty($apellidos) ||
                empty($cedula) || empty($email) ||
                empty($telefono) || empty($direccion) ||
                empty($usuario) || empty($clave) || empty($confirmar)
            ) {
                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {

                if ($clave != $confirmar) {
                    $msg = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');
                } else {
                    // Validación de contraseña segura
                    // super contraseña
                    //$isValid = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#.$($)$-$_])[A-Za-z\d$@$!%*?&#.$($)$-$_]{8,20}$/', $clave);
                    $isValid = preg_match('/^.{8,}$/u', $clave);

                    if (!$isValid) {
                        $msg = "insegura";
                    } else {


                        // Registrar usuario
                        $data = $this->model->registrarUsuario(
                            $nombres,
                            $apellidos,
                            $cedula,
                            $email,
                            $telefono,
                            $direccion,
                            $usuario,
                            $hash
                        );

                        // Evaluar si el registro fue exitoso
                        if ($data == "ok") {
                            $msg = "si";
                        } else if ($data == "existe") {
                            $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');
                        } else {
                            $msg = array('msg' => 'Error al registrar el usuario', 'icono' => 'warning');
                        }
                    }
                }
            }
        } else {

            //validacion de datos a insertar en la db (tal ves es mucho xd)
            if (
                empty($nombres) || empty($apellidos) ||
                empty($cedula) || empty($email) ||
                empty($telefono) || empty($direccion) ||
                empty($usuario)
            ) {

                $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
            } else {

                //llamamos al metodo que esta dentro de usuariosModel.PHP
                $data = $this->model->modificarUsuario(
                    $nombres,
                    $apellidos,
                    $cedula,
                    $email,
                    $telefono,
                    $direccion,
                    $usuario,
                    $id

                );

                //evaluamos si fue modificado con exito
                if ($data == "modificado") {

                    $msg = "modificado";
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al modificar el usuario', 'icono' => 'warning');
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

        $data = $this->model->editarUser($id);

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //funcion para Inhabilitar usuario
    public function eliminar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionUser(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Usuario inhabilitado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al inhabilitar usuario', 'icono' => 'error');
        }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //metodo para reingresar al usuario
    public function reingresar(int $id)
    {

        //almacenamos en data lo que retorno el metodo elimiaruser de usuariosModel.php
        $data = $this->model->accionUser(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Usuario reingresado con exito', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error reingresar usuario', 'icono' => 'error');
        }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function cambiarPass()
    {
        $actual = $_POST['clave_actual'];
        $nueva = $_POST['nueva_clave'];
        $confirmar = $_POST['confirmar_clave'];

        if (empty($actual) || empty($nueva) || empty($confirmar)) {

            $msg = array('msg' => 'Todo los campos son obligatorios', 'icono' => 'warning');
        } else if ($nueva != $confirmar) {

            $msg = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');
        } else {

            $id = $_SESSION['id_usuario'];
            $data = $this->model->getPass($id);

            //validacion para saber si la contrase;a actual es correcta
            if (password_verify($actual, $data['clave'])) {

                // super contraseña
                //$isValid = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#.$($)$-$_])[A-Za-z\d$@$!%*?&#.$($)$-$_]{8,20}$/', $clave);
                $isValid = preg_match('/^.{8,}$/u', $nueva);

                if (!$isValid) {

                    $msg = "insegura";
                } else {

                    $hash = password_hash($nueva, PASSWORD_BCRYPT);

                    $verificar = $this->model->modificarPass($hash, $id);

                    if ($verificar == 1) {
                        $msg = "ok";
                    } else {
                        $msg = array('msg' => 'Error al modificar la contraseña', 'icono' => 'error');
                    }
                }
            } else {
                $msg = array('msg' => 'La contraseña actual es incorrecta', 'icono' => 'warning');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function permisos()
    {
        $data = $this->model->getPermisos();

        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "permisos", $user_name, $data);
    }

    public function salir()
    {

        session_destroy();
        header("location: " . base_url);
    }
}
