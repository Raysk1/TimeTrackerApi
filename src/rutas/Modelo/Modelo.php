<?php
include BASE_DIR . "/src/config/Conexion.php";
class Modelo {



    public static function find($id) {
        $datos = array();
        try {
            if(is_string($id)){
                $id = "'$id'";
            }
            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $idName = static::class == "Usuario" ? "username" : "id";
            $query = "SELECT * FROM " . static::class . " WHERE $idName =" . $id;
            $rs = $conn->prepare($query);

            $rs->execute();
           

            //devuelve la fila convertida en un objeto de la clase pasada como parametro
            $dato = $rs->fetchObject(static::class);
            if ($dato == false) {
                $datos["error"] = ["code" => 404, "message" => static::class . " No Encontrado"];
                return $datos;
            }
            $datos = $dato;

            return $datos;
        } catch (Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }
    }

    public static function all() {

        $datos = array();
        try {
            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $query = "SELECT * FROM " . static::class;
            $rs = $conn->prepare($query);

            !$rs->execute();
             

            $data = array();

            //mete en el array mientras la consulta tenga filas
            while ($dato = $rs->fetchObject(static::class)) {
                array_push($data, $dato);
            }
            $datos = $data;
            // $datos["error"] = $error;
            return $datos;

        } catch (Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }

    }

    public function insert() {
        $datos = array();


        unset($this->id);
        //si el tipo es usuario el id = username en la tabla



        $vars = get_object_vars($this); //array con variables del objeto
        $table_name = get_class($this); //Nombre de la clase del objecto
        //si el id existe lo elimina

        $keys = array_keys($vars); //array con el nombre de los campos
        $values = ""; //llaves de valores a insertar (se inicializa con el valor del id)
        $params = [];
        $campos = "";
        //recorre el arreglo con todas las variables del objeto
        for ($i = 0; $i < count($vars); $i++) {
            $key = ":" . $keys[$i]; //nombre de la llave de params
            $values = $values . $key . ",";
            $campos = $campos . $keys[$i] . ",";
            if (empty(trim($vars[$keys[$i]]))) {
                $datos["error"] = ["code" => 400, "message" => "Datos Incompletos"];
                return $datos;
            }
            $params[$key] = $vars[$keys[$i]];
        }

        $values = mb_substr($values, 0, -1); //quitar la ultima coma
        $campos = mb_substr($campos, 0, -1); //quitar la ultima coma

        $query = "INSERT INTO " . $table_name . "(" . $campos . ") VALUES (" . $values . ")";
        try {
            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $rs = $conn->prepare($query);
            $rs->execute($params);


            //asignando id correspondiente al objeto
            /*
            $query = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'gym_hulk' AND TABLE_NAME = '" . $table_name . "'";
            $rs = $conn->prepare($query);
            $rs->execute();
            $last_id = $rs->fetch();
            $this->id = $last_id[0];
            */
            $this->id = $conn->lastInsertId();

        } catch (PDOException $e) {

            switch ($e->getCode()) {
                case 23000:
                    $datos["error"] = ["code" => 409, "message" => "Nombre de Usuario ya ocupado"];
                    break;
                default:
                    $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            }
            return $datos;
        }
    }

    public function delete() {
        $datos = array();
        try {
            $table_name = get_class($this); //Nombre de la clase del objecto
            $id = static::class == "Usuario" ? "'$this->id'" : $this->id;
            $idName = static::class == "Usuario" ? "username" : "id";
            $query = "DELETE FROM " . $table_name . " WHERE $idName = " . $id;

            $conexion = new Conexion();
            $conn = $conexion->conectar();

            $rs = $conn->prepare($query);
            $rs->execute();
            
            return $datos;
        } catch (\Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }

    }

    //para usar esta funcion el objeto debe tener un id
    public function update() {
        try {

            $vars = get_object_vars($this); //array con variables del objeto
            $table_name = get_class($this); //Nombre de la clase del objecto
            $keys = array_keys($vars); //array con el nombre de los campos
            $values = "";
            $params = [];

            for ($i = 0; $i < count($vars); $i++) {
                $key = ":" . $keys[$i]; //nombre de la llave de params
                if ($keys[$i] != "id" && $keys[$i] != "username") {
                    $values = $values . $keys[$i] . " = " . $key . ",";
                }
                $params[$key] = $vars[$keys[$i]];
            }

            $values = mb_substr($values, 0, -1); //quitar la ultima coma
            $id = static::class == "Usuario" ? "username" : "id";
            $query = "UPDATE " . $table_name . " SET " . $values . " WHERE $id = :$id";

            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $rs = $conn->prepare($query);
            $rs->execute($params);
             

        } catch (\Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }
    }
}
?>