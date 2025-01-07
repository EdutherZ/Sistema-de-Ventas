<?php


class Tasas extends Controller
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
        $data = $this->model->getTasas();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name, $data);
    }
    //metodo para listar usuarios
    public function listar()
    {

        $data = $this->model->listarTasas();

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($data, JSON_UNESCAPED_UNICODE); //ACEPATA ACENTOS
        die();
    }


    public function modificar()
    {
        $dolarx = $_POST['dolar'];
        $eurox = $_POST['euro'];
        $id_usuario = $_SESSION['id_usuario'];
        //$id = $_POST['id'];

        $dolar = str_replace(',', '.', $dolarx);
        $euro = str_replace(',', '.', $eurox);

        if (empty($dolar) || empty($euro)) {
            $msg = array('msg' => 'Todooos los campos son obligatorios', 'icono' => 'warning');
        } else {
            $data = $this->model->modificar($dolar, $euro, $id_usuario);
            if ($data == 'ok') {
                $msg = 'ok';
            } else {
                $msg = array('msg' => 'Error al modificar la tasa del dolar', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //funcion para actualizar la tasa del dolar de forma automatica
    public function actualizarTasaDolar()
    {
        //mega validaciones
        try {
            $url = "https://api.exchangerate-api.com/v4/latest/USD"; // URL de la API
            //extrae lo que hay dentro de $url y lo asigna a $json
            $json = @file_get_contents($url);
            if ($json === FALSE) {
                throw new Exception("No se pudo actualizar las tasas. Verifica tu conexión a internet.");
            }
            $data = json_decode($json, true);

            if (isset($data['rates']['VES'])) {
                $rateDolar = $data['rates']['VES']; // Tasa del dólar a bolívar venezolano

                // Obtener la tasa del euro a bolívar venezolano
                $urlEuro = "https://api.exchangerate-api.com/v4/latest/EUR"; // URL de la API para el euro
                $jsonEuro = @file_get_contents($urlEuro);
                if ($jsonEuro === FALSE) {
                    throw new Exception("No se pudo actualizar la tasa euro. Verifica tu conexión a internet.");
                }
                $dataEuro = json_decode($jsonEuro, true);

                if (isset($dataEuro['rates']['VES'])) {
                    $rateEuro = $dataEuro['rates']['VES']; // Tasa del euro a bolívar venezolano
                    $_POST['dolar'] = $rateDolar;
                    $_POST['euro'] = $rateEuro;

                    $this->modificar();
                } else {
                    throw new Exception("No se pudo obtener la tasa del euro");
                }
            } else {
                throw new Exception("No se pudo obtener la tasa del dólar");
            }
        } catch (Exception $e) {
            $msg = array('msg' => $e->getMessage(), 'icono' => 'error');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
}
