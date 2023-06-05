<?php
require BASE_DIR . "/src/rutas/Modelo/Horario.php";
class HorarioControlador {
    public function getAll(){
        return Horario::all();
    }

    public function get($id){
        return Horario::find($id);
    }

    public function insert(array $data){
        $horario = new Horario();
        $horario -> fecha_inicio = $data["fecha_inicio"];
        $horario -> fecha_fin = $data["fecha_fin"];
        $horario ->materia = $data["materia"];
        $horario -> id_usuario = $data["id_usuario"];

        $solapan = $horario->seSolapanFechas(false);
        if(is_array($solapan)){
            return $solapan;
        }elseif($solapan){
            $datos["error"] = ["code" => 409, "message" => "Ya existe un evento entre estas fechas"];
            return $datos;
        }else{
            $data = $horario -> insert();
            return is_array($data) ? $data : $horario;
        }

    
    }

    public function delete($id){
        $horario = new Horario();
        $horario -> id = $id;
        return $horario ->delete();
    }

    public function update(array $data){
        $horario = new Horario();
        $horario ->id = $data["id"];
        $horario -> fecha_inicio = $data["fecha_inicio"];
        $horario -> fecha_fin = $data["fecha_fin"];
        $horario ->materia = $data["materia"];
        $horario -> id_usuario = $data["id_usuario"];
        $solapan = $horario->seSolapanFechas(true);
        if(is_array($solapan)){
            return $solapan;
        }elseif($solapan){
            $datos["error"] = ["code" => 409, "message" => "Ya existe un evento entre estas fechas"];
            return $datos;
        }else{
            $data = $horario -> update();
            return is_array($data) ? $data : $horario;
        }

       
    }

 
}



?> 