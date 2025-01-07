<?php

//modelo del usuario
class InicioModel extends Query
{

    //CARGAMOS EL CONTRUCTOR DEL ARCHIVO QUERY.PHP
    public function __construct()
    {
        parent::__construct();
    }

    //consultamos los datos del usuario de la db
    public function getUsuario(string $email)
    {
        $sql = "SELECT p.*, u.id AS id_usuario, u.clave FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona WHERE p.email = '$email'";
        $data = $this->select($sql);
        return $data;
    }

    //consultamos los datos del usuario de la db
    public function getUsuarioEstado(string $email)
    {
        $sql = "SELECT  p.*, u.id AS id_usuario, u.clave FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona WHERE p.email = '$email' AND u.estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    //consultamos los datos del usuario de la db
    public function getDatos(string $table)
    {
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $data = $this->select($sql);
        return $data;
    }
    //consultamos los datos del usuario de la db
    public function getVentas()
    {
        $sql = "SELECT COUNT(*) AS total FROM ventas where fecha > CURDATE()";
        $data = $this->select($sql);
        return $data;
    }
    public function getTasa(){
        // Hacemos la consulta
        $sql = "SELECT * FROM tasas WHERE id = (SELECT MAX(id) FROM tasas)";
        $data = $this->select($sql);
        return $data;
    }

    

    public function validarRecuperacion(string $email, string $cedula)
    {

        $sql = "SELECT p.nombres, p.apellidos, u.id AS id_usuario FROM usuarios u INNER JOIN personas p ON p.id = u.id_persona  WHERE p.email = '$email' AND p.cedula = '$cedula' AND u.estado = 1";
        //accedemos a la funcion select que esta dentro de Query
        $data = $this->select($sql);

        return $data;
    }

    public function actulizarPass(string $clave, int $id)
    {


        $sql = "UPDATE usuarios set clave = ? where id = ?";

        $datos = array($clave, $id);

        $data = $this->save($sql, $datos);

        return $data;
    }
}
