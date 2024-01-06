<?php
    require_once 'config.php';
    require_once 'utils.php';
    require_once './models/Place.php';
    require_once  './controller/PlaceController.php';

    $method = $_SERVER['REQUEST_METHOD'];

    $controller = new PlaceController;

    if($method === 'POST') {
       $controller->create();

    } else if ($method === 'GET' && !isset($_GET['id'])) {
        $places = (new Place())->list();

        $allData = readFileContent(FILE_COUNTRY);
        response($allData, 200);

    } else if ($method === 'DELETE') {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id) {
            responseError('ID não encontrado', 400);
        }

        $place = new Place();
        $place->delete($id);
        
        response('Item deletado com sucesso', 204);

    } else if ($method === 'PUT') {
        $body = getBody();
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        $place = new Place();
        $place->update($id, $body);

    }else if ($method === 'GET' && $_GET['id']) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if(!$id) {
            responseError('ID não encontrado', 400);
        }

        $allData = readFileContent(FILE_COUNTRY);
        $place = new Place();
        $item = $place->listOne($id);
    }


?>