<?php
require BASE_DIR."/src/rutas/Modelo/Modelo.php";
class Usuario extends Modelo {
    public $username;
    public $password;
    public $tipo;
    public $nombre;
    public $apellidos;

    public function iniciarSesion() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM Usuario WHERE username='{$this->username}' AND password = '{$this->password}'";


        $datos = array();
        try {

            $rs = $conn->prepare($query);
            $rs->execute();


            $dato = $rs->fetchObject(Usuario::class);
            if ($dato == false) {
                $datos["error"] = ["code" => 401, "message" => "Username o Password Icorrecto"];
                return $datos;

            }

            /*
            $token = bin2hex(random_bytes(16));
            $query = "INSERT INTO token (id_usuario, token) VALUES ('{$dato->username}', '{$token}')";
            $rs = $conn->prepare($query);
            
            $rs->execute();
            */


            //$datos["data"] = $dato;
            /*
            $token = $rs->fetch();
            if($token == false){
            $error["error"] = true;
            $error["message"] ="Error al generar el token";
            array_push($datos,$error);
            return $datos;
            }
            */

            //  $datos["token"] = $token;

            return $dato;

        } catch (Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }

    }


}
?>