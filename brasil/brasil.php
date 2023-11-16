<?php
require_once 'config.php';
require_once 'utils.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $body = getBody();

    $name = sanitizeString($body->name);
    $contact = sanitizeString($body->contact);
    $opening_hours = sanitizeString($body->opening_hours);
    $description = sanitizeString($body->description);
    $latitude = filter_var($body->latitude, FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($body->longitude, FILTER_VALIDATE_FLOAT);


    if (!$name || !$contact || !$opening_hours || !$description || !$latitude || !$longitude) {
        responseError('Faltaram informações essenciais', 400);
    }

    $allData = readFileContent(FILE_COUNTRY);

    $data = [
        'id' => $_SERVER['REQUEST_TIME'], // Somente para uso didático
        'name' => $name,
        'contact' => $contact,
        'opening_hours' => $opening_hours,
        'description' => $description,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];

    array_push($allData, $data);
    saveFileContent(FILE_COUNTRY, $allData);
    response($data, 201);
} else if ($method === 'GET') {

    $allData = readFileContent(FILE_COUNTRY);

    response($allData, 200);
}
