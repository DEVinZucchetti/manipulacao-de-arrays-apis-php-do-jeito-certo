<?php 

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
            $place = (new Place())->setContact($contact);
            $place = (new Place())->setOpeningHours($opening_hours);
            $place = (new Place())->setDescription($description);
            $place = (new Place())->setLatitude($latitude);
            $place = (new Place())->setLongitude($longitude);
            $place = (new Place())->save();
    
            response($data, 201); 
        }
    }

?>