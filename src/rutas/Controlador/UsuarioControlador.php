<?php
require BASE_DIR . "/src/rutas/Modelo/Usuario.php";
class UsuarioControlador {

    public function logIn($username, $pass) {
        $user = new Usuario();
        //$user -> id = $_POST["id"];
        $user->username = $username;
        $user->password = $pass;
        //$user -> tipo = $_POST["tipo"];
        //$user -> id_persona = $_POST["id_persona"];
        $data = $user->iniciarSesion();
        return $data;
    }

    public function getAll() {
        return Usuario::all();
    }

    public function get($id) {
        return Usuario::find($id);
    }

    public function insert(array $data) {
        $user = new Usuario();
        $user->username = $data["username"];
        $user->password = $data["password"];
        $user->tipo = $data["tipo"];
        $user->nombre = $data["nombre"];
        $user->apellidos = $data["apellidos"];
        $data = $user->insert();

        return is_array($data) ? $data : $user;

    }

    public function delete($id) {
        $user = new Usuario();
        $user->id = $id;

        return $user->delete();
    }

    public function update(array $data) {
        $user = new Usuario();
        $user->username = $data["username"];
        $user->password = $data["password"];
        $user->tipo = $data["tipo"];
        $user->nombre = $data["nombre"];
        $user->apellidos = $data["apellidos"];
        $data = $user->update();
        return is_array($data) ? $data : $user;
    }

}
?>