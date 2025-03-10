<?php

//modelo del usuario
class ClientesModel extends Query
{
    private $nombres,   $apellidos, $cedula,
        $email, $telefono,
        $direccion, $id, $estado;

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }


    //metodo para traer(listar) los usuarios de la db 
    public function getClientes()
    {
        //seleccionamos la tabla
        $sql = "SELECT p.*, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo , c.*  FROM clientes c INNER JOIN personas p ON p.id = c.id_persona";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->selectAll($sql);

        return $data;
    }

    //funcion para registrar los datos del usuario
    public function registrarClientes(
        string $nombres,
        string $apellidos,
        string $cedula,
        string $email,
        string $telefono,
        string $direccion
    ) {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;

        // Consulta para verificar si la persona ya existe
        //se puede colocar (cedula = '$this->cedula') encaso de que email sea campo unico para todos los tipos de personas y los clietnes no pudieran se usuarios
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

        // Consulta para verificar si el cliente ya existe
        $verificarCliente = "SELECT * FROM clientes WHERE id_persona = '$id_persona'";
        $clienteExistente = $this->select($verificarCliente);

        if (empty($clienteExistente)) {
            // Registrar cliente
            $sqlCliente = "INSERT INTO clientes (id_persona)
                           VALUES (?)";
            $datosCliente = array(
                $id_persona
            );
            $data = $this->save($sqlCliente, $datosCliente);

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
    public function modificarClientes(
        string $nombres,
        string $apellidos,
        string $cedula,
        string $email,
        string $telefono,
        string $direccion,
        int $id
    ) {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->id = $id;

        $resultado = "error"; // Variable para almacenar el resultado

        // Obtener el id_persona a partir del id
        $verificarUsuario = "SELECT id_persona FROM clientes WHERE id = '$this->id'";
        $usuarioExistente = $this->select($verificarUsuario);

        if (!empty($usuarioExistente)) {
            $idPersona = $usuarioExistente['id_persona'];

            // Verificación de existencia de persona
            //se puede colocar (cedula = '$this->cedula') encaso de que email sea campo unico para todos los tipos de personas y los clietnes no pudieran se usuarios
            $verificarPersona = "SELECT COUNT(*) as count FROM personas WHERE (cedula = '$this->cedula' OR email = '$this->email') AND id != '$idPersona'";
            $personaExistente = $this->select($verificarPersona);

            if ($personaExistente['count'] == 0) {
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

                    // Actualizar cliente
                    $sqlCliente = "UPDATE clientes SET fyh_act = CURRENT_TIMESTAMP	 WHERE id = ?";
                    $datosCliente = array($this->id,);

                    $dataCliente = $this->save($sqlCliente, $datosCliente);

                    if ($dataCliente == 1) {
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
    public function editarCli(int $id)
    {

        $sql = "SELECT p.* , c.* FROM clientes c INNER JOIN personas p ON p.id = c.id_persona WHERE c.id = $id";
        $data = $this->select($sql);
        return $data;
    }

    //funcion para inhabilitar y reingresar el usuario fuaa
    public function accionCli(int $estado, int $id)
    {
        //asignamos el valor de id y estado
        $this->id = $id;
        $this->estado = $estado;
        //actualizamos el estado
        $sql = "UPDATE clientes set estado = ? where id = ?";
        //almacenamos el estado y id en datos
        $datos = array($this->estado, $this->id);
        //le enviamos a query.php el sql y los datos
        $data = $this->save($sql, $datos);
        //a data se le asigna lo que devulva query.ph
        //retornamos data
        return $data;
    }
}
