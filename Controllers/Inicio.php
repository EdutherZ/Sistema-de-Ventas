<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//clase de la pagina de inicio
class Inicio extends Controller
{

    public function __construct()
    {
        session_start();



        parent::__construct();
    }

    public function index()
    {
        //ESTA AQUI PARA EVITAR ERRORES AL INICIAR SESION
        //validacion para el inicio de sesion de los usuarios activos o no
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        $data['usuarios'] = $this->model->getDatos('usuarios');
        $data['clientes'] = $this->model->getDatos('clientes');
        $data['productos'] = $this->model->getDatos('productos');
        $data['ventas'] = $this->model->getVentas();
        $data['tasa'] = $this->model->getTasa();
        $user_name = $_SESSION['nombres'];
        $this->views->getView($this, "index", $user_name, $data);
    }


    /*LA FUNCION VALIDAR ESTA AQUI Y NO EN USUARIOS PARA
    //TENERMAYOR SEGURIDAD Y NO PUEDAN ACCEDER A NADA SIN HABER INICIADO SESION*/
    public function validar()
    {
        if (empty($_POST['email']) || empty($_POST['clave'])) {
            $msg = "Los campos están vacíos";
        } else {
            $email = $_POST['email'];
            $clave = $_POST['clave'];

            // Obtenemos el hash almacenado en la base de datos
            $data = $this->model->getUsuario($email);
            $estado = $this->model->getUsuarioEstado($email);

            if ($data && $estado) {
                // Verificamos la contraseña ingresada con el hash almacenado
                if (password_verify($clave, $data['clave'])) {

                    $_SESSION['id_usuario'] = $data['id_usuario'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['nombres'] = $data['nombres'];
                    $_SESSION['activo'] = true;

                    $msg = "ok";
                } else {
                    $msg = array('msg' => 'Correo Electrónico o Clave Incorrecto', 'icono' => 'warning');
                }
            } elseif ($data && !$estado) {

                $msg = array('msg' => 'Usuario Inactivo', 'icono' => 'error');
            } else {

                $msg = array('msg' => 'Correo Electrónico o Clave Incorrecto', 'icono' => 'warning');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function recuperar()
    {
        $this->views->getView($this, "recuperar");
    }

    public function recuperarPass()
    {

        $email = $_POST['email'];
        $cedula = $_POST['cedula'];

        if (empty($email) || empty($cedula)) {

            $msg = array('msg' => 'Faltan campos por completar', 'icono' => 'warning');
        } else {
            $data = $this->model->validarRecuperacion($email, $cedula);
           // $data = mb_convert_encoding($datax, 'ISO-8859-1', 'UTF-8');
            
            //DATOS DE LA SECCION?
            if ($data) {


                function generarContrasenaSegura($longitud = 15)
                {
                    //supeeeeerrrrrr
                    //$caracteres = 'áéíóúÁÉÍÓÚñÑ0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
                    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $cantidadCaracteres = mb_strlen($caracteres, 'UTF-8');
                    $contrasena = '';

                    for ($i = 0; $i < $longitud; $i++) {
                        $contrasena .= mb_substr($caracteres, random_int(0, $cantidadCaracteres - 1), 1, 'UTF-8');
                    }

                    return $contrasena;
                }


                function tiene_conexion_internet()
                {
                    $conectado = @fsockopen("www.google.com", 80);
                    if ($conectado) {
                        fclose($conectado);
                        return true;
                    } else {
                        return false;
                    }
                }

                // Llamada a la función para obtener una contraseña de 15 caracteres
                $contrasena = generarContrasenaSegura();

                //$contrasena = mb_convert_encoding($contrasenax, 'ISO-8859-1', 'UTF-8');

                $hash = password_hash($contrasena, PASSWORD_BCRYPT);

                require 'Libraries/PHPMailer/src/Exception.php';
                require 'Libraries/PHPMailer/src/PHPMailer.php';
                require 'Libraries/PHPMailer/src/SMTP.php';

                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->CharSet = 'UTF-8';
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
                    $mail->isSMTP();                                          
                    $mail->Host       = 'smtp.gmail.com';                     
                    $mail->SMTPAuth   = true;                                  
                    $mail->Username   = '@gmail.com';
                    $mail->Password   = '';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
                    $mail->Port       = 465;                                    

                    //Recipients
                    $mail->setFrom('@gmail.com', 'Edutherz');
                    $mail->addAddress($email, $data['nombres']);


                    //Content
                    $mail->isHTML(true); 
                    $mail->Subject = 'Recuperacion de Contraseña';
                    $mail->Body    = '<p>Hola <strong>' . $data['nombres'] . ' ' . $data['apellidos'] . '</strong></p>
                                    <p>Su nueva contraseña es <strong>' . $contrasena . '</strong>, 
                                    le recomendamos que al ingresar al sistema proceda a cambiarla dirigiéndose al 
                                    <strong>MENÚ DE OPCIONES</strong> en la opción 
                                    <strong>&lt;&lt; Configuracion -&gt; Cambiar Contraseña &gt;&gt;</strong></p>
                                    <p><strong>IMPORTANTE:</strong></p>
                                    <ol>
                                    <li><p>Los administradores del sistema
                                     no se hacen responsables de cualquier uso inapropiado que se le dé a la CONTRASEÑA, ni responderán
                                    por las consecuencias del uso ilegítimo, por terceros o de las claves de acceso al sistema.</p></li>
                                    </ol>
                                    <p><strong>Gracias por utilizar nuestro sistema :)</strong></p>';

                    $mail->Body = mb_convert_encoding($mail->Body, 'ISO-8859-1', 'UTF-8');
                    $mail->send();

                    $this->model->actulizarPass($hash, $data['id_usuario']);

                    $msg = "ok";
                } catch (Exception $e) {

                    if (strpos($mail->ErrorInfo, 'Invalid address') !== false) {
                        $msg = array('msg' => 'El correo electronico no existe', 'icono' => 'error');
                    } elseif (!tiene_conexion_internet()) {
                        $msg = array('msg' => 'No hay conexión a internet', 'icono' => 'error');
                    } else {
                        $msg = array('msg' => "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}", 'icono' => 'error');
                    }
                }
            } else {

                $msg = array('msg' => 'Los datos son incorrectos', 'icono' => 'error');
            }
        }

        //ENVIAMOS LA DATA AL ARCHIVO FUNCIONES.JS
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
