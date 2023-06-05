<?php
require BASE_DIR . "/src/rutas/Modelo/Modelo.php";
class Horario extends Modelo {
    public $id;
    public $fecha_inicio;
    public $fecha_fin;
    public $materia;
    public $id_usuario;

    public function seSolapanFechas(bool $useId) {
        try {
            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $sql = "SELECT * FROM Horario WHERE fecha_inicio < :fecha_fin AND fecha_fin > :fecha_inicio";
            $data = array(':fecha_inicio' => $this->fecha_inicio, ':fecha_fin' => $this->fecha_fin);
            if($useId){
                $data[":id"] = $this->id;
                $sql = $sql . " AND id != :id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->rowCount() > 0;
        } catch (Throwable $th) {
            $datos["error"] = ["code" => 500, "message" => "Internal Server Error"];
            return $datos;
        }
    }
}


?>