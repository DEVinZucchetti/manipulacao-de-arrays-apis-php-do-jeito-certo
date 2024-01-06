<?php 

    require_once '../utils.php';
    require_once './api_brasil_semana05/models/PlaceDAO.php';
    require_once './api_brasil_semana05/models/Place.php';

    class PlaceController {
        public function create() {
            $body = getBody();

            $name = sanitizeString($body->name);
            $contact = sanitizeString($body->contact);
            $opening_hours = sanitizeString($body->opening_hours);
            $description = sanitizeString($body->description);
            $latitude = filter_var($body->latitude, FILTER_VALIDATE_FLOAT);
            $longitude = filter_var($body->longitude, FILTER_VALIDATE_FLOAT);
    
            if(!$name || !$contact || !$opening_hours || !$description || !$latitude || !$longitude) {
                responseError('todos os campos são de preenchimento obrigatório', 400);
            }
    
            $allData = readFileContent(FILE_COUNTRY);
    
            $itemWithSameName = array_filter($allData, function ($item) use ($name){
                return $item->name === $name;
            });
    
            if(count($itemWithSameName) > 0) {
                responseError("Esse item já foi cadstrado", 409);
            }
    
            $place = new Place($name);
            $place = setContact($contact);
            $place = setOpeningHours($opening_hours);
            $place = setDescription($description);
            $place = setLatitude($latitude);
            $place = setLongitude($longitude);
            $place = save();
    
            response($data, 201); 
        }

        public function list()
        {
            $placeDAO = new PlaceDAO();
            $result->findMany();
            response($result, 200);
        }

        public function delete(){
            $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

            if(!$id) {
                responseError('ID ausente', 400);
            }

            $placeDAO = new PlaceDAO();
            $placeDAO = deleteOne($id);

            response(['message' => 'Lugar deletado com sucesso'], 204);
        }

        public function listOne(){
            $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$id) {
                responseError('ID ausente', 400);
            }

            $placeDAO = new PlaceDAO();
            $item = $placeDAO->listOne($id);

            response($item, 200);
        }
    }

?>