<?php
class Conexion {
    //private $user  = 'id20317311_root';
    //private $pass = "Nr9EWUoLass+d-NO";
    private $pass = "";
    private $user = "root";
    private $database  = "TimeTracker";
    public function conectar() {
        try {
            $conn = new PDO("mysql:host=localhost;dbname={$this->database}" , $this->user, $this->pass);
            $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (Throwable $th) {
            //throw $th;
            die("Error failed to connect to DataBase");
        }
    }
}

?>