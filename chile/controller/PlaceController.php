<?php
// Importar arquivos:
require_once '../utils.php';
require_once '../models/Place.php';
require_once '../models/PlaceDAO.php';

class PlaceController
{

    //Cria um novo item
    public function create()
    {
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
            responseError('Faltaram informações essenciais', 400);
        }

        // $allData = readFileContent(FILE_CITY);

        // // Verifica se o item já existe:
        // $itemWithSameName = array_filter($allData, function ($item) use ($name) {
        //     return $item->name === $name;
        // });

        // if (count($itemWithSameName) > 0) {
        //     responseError('Lugar já cadastrado', 409);
        // }

        $place = new Place($name);
        $place->setContact($contact);
        $place->setOpeningHours($opening_hours);
        $place->setDescription($description);
        $place->setLatitude($latitude);
        $place->setLongitude($longitude);

        $placeDAO = new PlaceDAO();

        $result = $placeDAO->insert($place);

        if ($result['success'] === true) {
            response(["message" => "Lugar cadastrado com sucesso"], 201);
        } else {
            responseError("Não foi possível realizar o cadastro", 400);
        }

    }

    //Lista os itens
    public function list()
    {
        $placeDAO = new PlaceDAO();
        $result = $placeDAO->findMany();
        response($result, 200);
    }

    //Deleta um item com base no id
    public function delete()
    {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) {
            responseError('ID ausente', 400);
        }

        $placeDAO = new PlaceDAO();
        $placeDAO->deleteOne($id);

        response(['message' => 'Deletado com sucesso'], 204);
    }

    //Lista um item com base no id
    public function listOne()
    {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) {
            responseError('ID ausente', 400);
        }

        $PlaceDAO = new PlaceDAO();
        $item = $PlaceDAO->findOne($id);

        response($item, 200);
    }
    
    //Atualiza qualquer informação do item
    public function update()
    {
        $body = getBody();
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) {
            responseError('ID ausente', 400);
        }

        $placeDAO = new PlaceDAO();
        $placeDAO->updateOne($id, $body);
        response(['message' => 'Lugar atualizado com sucesso'], 200);
    }
}