<?php
    require_once 'config.php';
    require_once 'utils.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if($method === 'POST') {
        $body = getBody();

        $name = sanitizeString($body->name);
        $contact = sanitizeString($body->contact);
        $opening_hours = sanitizeString($body->opening_hours);
        $description = sanitizeString($body->description);
        $latitude = filter_var($body->$latitude, FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($body->$longitude, FILTER_VALIDATE_FLOAT);

        if(!$name || !$contact || !$opening_hours || !$description || !$latitude || !$longitude) {
            responseError('todos os campos são de preenchimento obrigatório', 400);
        }

        $allData = readFileContent('FILE_CITY');

        $itemWithSameName = array_filter($allData, function ($item) use ($name){
            return $item->name == $name;
        });

        if(count($itemWithSameName) > 0) {
            responseError("Esse item já foi cadstrado", 409);
        }

        $data = [
            'id' => $_SERVER['REQUEST_TIME'],
            'name' => $name,
            'contact' => $contact,
            'opening_hours' => $opening_hours,
            'description' => $description,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        array_push($allData, $data);
        saveFileContent('FILE_CITY', $data);

        response($data, 201); 

    } else if ($method === 'GET') {
        $allData = readFileContent('FILE_CITY');
        response($allData, 200);

    } else if ($method === 'DELETE') {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id) {
            responseError('ID não encontrado', 400);
        }

        $allData = readFileContent('FILE_CITY');

        $filterItem = array_filter($allData, function($item) use ($id){
            if($item->id !== $id)
            return $item;
        });

        saveFileContent(FILE_CITY, $filterItem);
        
        response('Item deletado com sucesso', 204);

    } else if ($method === 'PUT') {
        $body = getBody();
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        $allData = readFileContent(FILE_CITY);

        foreach($allData as $item => $position) {
            if($item->id === $id) {
                $allData[$position]->name = $body->name;
            }
        }

    }else if ($method === 'GET' && $_GET['id']) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if(!$id) {
            responseError('ID não encontrado', 400);
        }

        $allData = readFileContent(FILE_CITY);

        foreach($allData as $item) {
            if($item->id === $id) {
                response($item, 200);
            }
        }
    }


?>