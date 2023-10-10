<?php
// Importar arquivos:
require_once 'config.php';
require_once 'utils.php';

// Capturar metodo:
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Pegar o body
    $body = getBody();
    // Fazer a validação
    $name = sanitizeString($body->name);
    $contact = sanitizeString($body->contact);
    $opening_hours = sanitizeString($body->opening_hours);
    $description = sanitizeString($body->description);
    $latitude = filter_var($body->latitude, FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($body->longitude, FILTER_VALIDATE_FLOAT);

    if (!$name || !$contact || !$opening_hours || !$description || !$latitude || !$longitude) {
        responseError('Faltaram informações essenciais', 400); // Retorna ao usuário
    }

    $allData = readFileContent(FILE_COUNTRY);

    // Se o item já existir:
    foreach ($allData as $place) {
        if ($place->name === $name) {
            http_response_code(409);
            echo json_encode(['error' => 'Lugar já cadastrado.']);
            exit;
        }
    }

    $data = [
        'id' => $_SERVER['REQUEST_TIME'], // SOMENTE PARA ESSE EXERCICIO
        'name' => $name,
        'contact' => $contact,
        'opening_hours' => $opening_hours,
        'description' => $description,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];

    $allData = readFileContent(FILE_COUNTRY); // Lê o que tem no arquivo
    array_push($allData, $data); // Insere nova informação
    saveFileContent(FILE_COUNTRY, $allData);

    response($data, 201);
} elseif ($method === 'GET') {
    $allData = readFileContent(FILE_COUNTRY);
    response($allData, 200); // Método GET lê o arquivo e retorna 200

} elseif ($method === 'DELETE') {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id) {
        responseError('ID ausente', 400);
    }
    $allData = readFileContent(FILE_COUNTRY);

    $itemsFiltered = array_filter($allData, function ($item) use ($id) {
        if ($item->id !== $id);
    });

    saveFileContent(FILE_COUNTRY, $itemsFiltered);
    response(['message' => 'Deletado com sucesso'], 204);
} elseif ($method === 'GET' && $_GET['id']) { // Buscar lugar
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id) {
        responseError('ID ausente', 400);
    }

    $allData = readFileContent(FILE_COUNTRY);

    foreach ($allData as $item) {
        if ($item->id === $id) {
            response($item, 200);
        }
    }
} elseif ($method === 'PUT') { // Atualizar lugar
    $body = getBody();
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id) {
        responseError('ID ausente', 400);
    }

    $allData = readFileContent(FILE_COUNTRY);

    foreach ($allData as $position => $item) {
        if ($item->id === $id) {
            $allData[$position]->name =  isset($body->name) ? $body->name : $item->name;
            $allData[$position]->contact =  isset($body->contact) ? $body->contact : $item->contact;
            $allData[$position]->opening_hours =   isset($body->opening_hours) ? $body->opening_hours : $item->opening_hours;
            $allData[$position]->description =  isset($body->description) ? $body->description : $item->description;
            $allData[$position]->latitude =  isset($body->latitude) ? $body->latitude : $item->latitude;
            $allData[$position]->longitude =  isset($body->longitude) ? $body->longitude : $item->longitude;
        }
    }



    saveFileContent(FILE_COUNTRY, $allData);
    response([], 200);
}