<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//$app = new \Slim\App;
header("Content-Type: application/json; charset=UTF-8");
$app->addBodyParsingMiddleware();


$app->get("/api/img/usuario/{id}", function (Request $request, Response $response, array $args) {
    $directory = BASE_DIR . "/src/img/usuarios";
    $filename = $args["id"];
    $files = glob("$directory/$filename.*");
    if(count($files) == 0){
        return $response->withStatus(404, "La imagen no existe");
    }
    $image = file_get_contents($files[0]);
    if ($image != false) {
        $response = $response->withHeader('Content-Type', 'image/png');
       $response->getBody()->write($image);
       return $response;
    }else{
        return $response->withStatus(500, "Error al acceder a la imagen");
    }
    


});

$app->post("/api/img/usuario/{id}", function (Request $request, Response $response, array $args) {
    $directory = BASE_DIR . "/src/img/usuarios";

    $uploadedFiles = $request->getUploadedFiles();

    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['image'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        moveUploadedFile($directory, $uploadedFile, $args["id"]);
        return $response->withStatus(201, "Imagen cargada correctamente");
    } else {
        return $response->withStatus(500, "Error al cargar la imagen");
    }
});

$app->post("/api/login", function (Request $request, Response $response) {
    require BASE_DIR . "/src/rutas/Controlador/UsuarioControlador.php";
    $controlador = new UsuarioControlador();
    $datos = $request->getParsedBody();
    $data = $controlador->logIn($datos["username"], $datos["password"]);
    if (is_array($data) && isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        $response->getBody()->write(json_encode($data));
        return $response;
    }
});

$app->get("/api/{tabla}", function (Request $request, Response $response, array $args) {
    $class = $args["tabla"] . "Controlador";
    $ruta = BASE_DIR . "/src/rutas/Controlador/$class.php";
    require $ruta;
    $controlador = new $class();
    $data = $controlador->getAll();
    if (isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        $response->getBody()->write(json_encode($data));
        return $response;
    }
});

$app->get("/api/{tabla}/{id}", function (Request $request, Response $response, array $args) {
    $class = $args["tabla"] . "Controlador";
    $ruta = BASE_DIR . "/src/rutas/Controlador/$class.php";
    require $ruta;
    $controlador = new $class();
    $id = $args['id'];
    $data = $controlador->get($id);

    if (is_array($data) && isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        $response->getBody()->write(json_encode($data));
        return $response;
    }
});

$app->post("/api/{tabla}", function (Request $request, Response $response, array $args) {
    $class = $args["tabla"] . "Controlador";
    $ruta = BASE_DIR . "/src/rutas/Controlador/$class.php";
    require $ruta;
    $controlador = new $class();
    $datos = json_decode($request->getBody(), true);
    $data = $controlador->insert($datos);
    if (is_array($data) && isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(201);
    }
});



$app->delete("/api/{tabla}/{id}", function (Request $request, Response $response, array $args) {
    $class = $args["tabla"] . "Controlador";
    $id = $args["id"];
    $ruta = BASE_DIR . "/src/rutas/Controlador/$class.php";
    require $ruta;
    $controlador = new $class();
    $data = $controlador->delete($id);
    if (is_array($data) && isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        return $response->withStatus(201);
    }
});

$app->put("/api/{tabla}", function (Request $request, Response $response, array $args) {
    $class = $args["tabla"] . "Controlador";
    $ruta = BASE_DIR . "/src/rutas/Controlador/$class.php";
    require $ruta;
    $controlador = new $class();
    $datos = json_decode($request->getBody(), true);
    $data = $controlador->update($datos);
    if (is_array($data) && isset($data["error"])) {
        return $response->withStatus($data["error"]["code"], $data["error"]["message"]);
    } else {
        $response->getBody()->write(json_encode($data));
        return $response;
    }

});

function moveUploadedFile($directory, $uploadedFile, $filename) {
    $files = glob("$directory/$filename.*");
    array_map("unlink", $files);
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    //$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = "$filename.$extension";

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}



?>