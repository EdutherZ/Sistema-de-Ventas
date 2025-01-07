<?php

//modelo del usuario
class UsuariosModel extends Query
{
    private $nombres,   $apellidos, $cedula,
        $email, $telefono,
        $direccion, $usuario, $clave, $id, $estado;

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }

    //metodo para traer(listar) los usuarios de la db 
    public function getUsuarios()
    {
        /*Esta consulta te devolverá todos los campos de la tabla usuarios, 
        junto con una columna adicional llamada nombre_completo
        que contiene la concatenación del nombre y el apellido.*/

        $sql = "SELECT p.*, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo, u.*, (SELECT MIN(id) FROM usuarios) AS min_id FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->selectAll($sql);
        return $data;
    }

    //funcion para registrar los datos del usuario
    public function registrarUsuario(
        string $nombres,
        string $apellidos,
        string $cedula,
        string $email,
        string $telefono,
        string $direccion,
        string $usuario,
        string $clave
    ) {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->usuario = $usuario;
        $this->clave = $clave;

        /*/codigo para hacer que email se campo unico solo para el usuario y no el cliente
        $verificarPersonax = "SELECT p.* FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona WHERE email = '$this->email'";
        $personaExistentex = $this->select($verificarPersonax);*/

        // Consulta para verificar si la persona ya existe
        //se puede colocar (cedula = '$this->cedula') encaso de que email sea campo unico para todos los tipos de personas y los usuarios  no pudieran se  clietnes          
        $verificarPersona = "SELECT * FROM personas WHERE cedula = '$this->cedula' OR email = '$this->email'";
        $personaExistente = $this->select($verificarPersona);

        if (empty($personaExistente)) {
            // Registrar nueva persona
            $sqlPersona = "INSERT INTO personas (nombres, apellidos, cedula, email, telefono, direccion)
                           VALUES (?, ?, ?, ?, ?, ?)";
            $datosPersona = array(
                $this->nombres,
                $this->apellidos,
                $this->cedula,
                $this->email,
                $this->telefono,
                $this->direccion
            );
            $data = $this->save($sqlPersona, $datosPersona);

            // Validación de lo que devuelve save()
            if ($data == 1) {

                $id_persona = $this->getLastInserId();
            } else {
                $res = "error";
            }
        } else {

            $id_persona = $personaExistente['id'];
        }

        // Consulta para verificar si el usuario ya existe
        $verificarUsuario = "SELECT * FROM usuarios WHERE id_persona = '$id_persona'";
        $usuarioExistente = $this->select($verificarUsuario);

        if (empty($usuarioExistente)) {
            // Registrar usuario
            $sqlUsuario = "INSERT INTO usuarios (id_persona, usuario, clave)
                           VALUES (?, ?, ?)";
            $datosUsuario = array(
                $id_persona,
                $this->usuario,
                $this->clave
            );
            $data = $this->save($sqlUsuario, $datosUsuario);

            // Validación de lo que devuelve save()
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        // Capturamos $res en funciones.js
        return $res;
    }


    //funcion para registrar los datos del usuario
    public function modificarUsuario(
        string $nombres,
        string $apellidos,
        string $cedula,
        string $email,
        string $telefono,
        string $direccion,
        string $usuario,
        int $id
    ) {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->usuario = $usuario;
        $this->id = $id;

        $resultado = "error"; // Variable para almacenar el resultado

        // Obtener el id_persona a partir del id
        $verificarUsuario = "SELECT id_persona FROM usuarios WHERE id = '$this->id'";
        $usuarioExistente = $this->select($verificarUsuario);

        if (!empty($usuarioExistente)) {
            $idPersona = $usuarioExistente['id_persona'];

            /*/codigo para hacer que email se campo unico solo para el usuario y no el cliente
            $verificarPersonax = "SELECT COUNT(*) as count FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona WHERE email = '$this->email'";
            $personaExistentex = $this->select($verificarPersonax);*/

            // Verificación de existencia de persona 
            //se puede colocar (cedula = '$this->cedula') encaso de que email sea campo unico para todos los tipos de personas y los usuarios  no pudieran se  clietnes          
            $verificarPersona = "SELECT COUNT(*) as count FROM personas WHERE (cedula = '$this->cedula' OR email = '$this->email') AND id != '$idPersona'";
            $personaExistente = $this->select($verificarPersona);

            if ($personaExistente['count'] === 0) {
                // Actualizar persona
                $sqlPersona = "UPDATE personas SET nombres = ?, apellidos = ?, cedula = ?, email = ?, telefono = ?, direccion = ? WHERE id = ?";
                $datosPersona = [
                    $this->nombres,
                    $this->apellidos,
                    $this->cedula,
                    $this->email,
                    $this->telefono,
                    $this->direccion,
                    $idPersona
                ];
                $dataPersona = $this->save($sqlPersona, $datosPersona);

                if ($dataPersona == 1) {

                    // Actualizar usuario
                    $sqlUsuario = "UPDATE usuarios SET usuario = ?, fyh_act = CURRENT_TIMESTAMP WHERE id = ?";
                    $datosUsuario = array($this->usuario, $this->id);

                    $dataUsuario = $this->save($sqlUsuario, $datosUsuario);

                    if ($dataUsuario == 1) {
                        $resultado = "modificado";
                    }
                }
            } else {
                $resultado = "existe";
            }
        }

        return $resultado;
    }

    //metodo para editar al usuario mediante su ID
    public function editarUser(int $id)
    {
        $sql = "SELECT p.*, u.id AS id_usuario, u.id_persona, u.clave, u.usuario  FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona WHERE u.id = $id";
        $data = $this->select($sql);
        return $data;
    }

    //funcion para inhabilitar y reingresar el usuario fuaa
    public function accionUser(int $estado, int $id)
    {
        //asignamos el valor de id y estado
        $this->id = $id;
        $this->estado = $estado;
        //actualizamos el estado
        $sql = "UPDATE usuarios set estado = ? where id = ?";
        //almacenamos el estado y id en datos
        $datos = array($this->estado, $this->id);
        //le enviamos a query.php el sql y los datos
        $data = $this->save($sql, $datos);
        //a data se le asigna lo que devulva query.ph
        //retornamos data
        return $data;
    }
    //metodo para traer la clave del usuario
    public function getPass(int $id)
    {

        $sql = "SELECT clave FROM usuarios where id = $id";
        $data = $this->select($sql);
        return $data;
    }

    //metodo para modificar la clave del usuario
    public function modificarPass(string $clave, int $id)
    {

        $sql = "UPDATE usuarios set clave = ? where id = ?";
        $datos = array($clave, $id);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function getPermisos()
    {

        $sql = "SELECT * FROM permisos";
        $data = $this->selectAll($sql);
        return $data;
    }
}
