<?php
require BASE_DIR . "/src/rutas/Modelo/Materia.php";
class MateriaControlador {
    public function getAll() {
        return Materia::all();
    }

    public function get($id){
        return Materia::find($id);
    }

    public function insert(array $data){
        $materia = new Materia();
        $materia -> carrera = $data["carrera"];
        $materia -> grupo = $data["grupo"];
        $materia->nombre = $data["nombre"];

        $data = $materia->insert();

        return is_array($data) ? $data : $materia;

    }

    public function delete($id){
        $materia = new Materia();
        $materia->id = $id;
        return $materia->delete();
    }

    public function update(array $data){
        $materia = new Materia();
        $materia -> carrera = $data["carrera"];
        $materia -> grupo = $data["grupo"];
        $materia->nombre = $data["nombre"];
        $materia -> id = $data["id"];
        $data = $materia->update();

        return is_array($data) ? $data : $materia;
    }
}
?>