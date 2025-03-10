<?php


class Administracion extends Controller
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
        $data = $this->model->getEmpresa();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name, $data);
    }

    public function modificar()
    {
        $rif = $_POST['rif'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $mensaje = $_POST['mensaje'];
        $id = $_POST['id'];

        $validcedula = preg_match('/^(?=.*[A-Za-z0-9]).{8,9}$/', $rif);
        $validtelef = preg_match('/^(?=.*[A-Za-z0-9]).{11,11}$/', $telefono);

        if (empty($rif) || empty($nombre) || empty($telefono) || empty($direccion) || empty($mensaje) || empty($id)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
            
        } else if (!$validcedula || !is_numeric($rif)) {

            $msg = array('msg' => 'RIF no valido', 'icono' => 'warning');
        } else if (!$validtelef || !is_numeric($telefono)) {

            $msg = array('msg' => 'Telefono no valido', 'icono' => 'warning');
        } else {

            $data = $this->model->modificar($rif,  $nombre,  $telefono,  $direccion,  $mensaje, $id);
            if ($data == 'ok') {

                $msg = 'ok';
            } else {

                $msg = array('msg' => 'Error al modificar los datos de la empresa', 'icono' => 'error');
            }
        }
        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
