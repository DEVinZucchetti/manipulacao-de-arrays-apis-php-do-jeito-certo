<?php

    class PlaceDAO {
        private $connection;

        public function __construct() {
            $this->connection = new PDO("pgsql:host=localhost; dbname:api_places", "admin", "admin");
        }


        public function insert(Place $place){
            try{
                $sql = "insert into places 
                            (
                                name,
                                contact,
                                opening_hours,
                                description,
                                latitude,
                                longitude
                            )
                            values
                            (
                                :name_value,
                                :contact_value,
                                :opening_hours_value,
                                :description_value,
                                :latitude_value,
                                :longitude_value
                            );
                ";

                $statement = ($this->getConnection())->prepare($sql);

                $statement = bindValue(":name_value", $place->getName());
                $statement = bindValue(":contact_value", $place->getContact());
                $statement = bindValue(":opening_hours_value", $place->getOpeningHours());
                $statement = bindValue(":description_value", $place->getDescription());
                $statement = bindValue(":latitude_value", $place->getLatitude());
                $statement = bindValue(":longitude_value", $place->getLongitude());

                $statement = execute();

                return ['success' => true];} catch (PDOException $error){
                    debug($error->getMessage());
                    return ['success' => false];
                }

        }

        public function findMany() {
            $sql = " select * from places, order by name";

            $statement = ($this->getConnection())->prepare($sql);

            $statement = execute();

            return $statement-> fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteOne(){
            $sql = "delete from places where id = :id_value";
            $statement = ($this->getConnection())->prepare($sql);
            $statement = bindValue(":id_value", $id);
            $statement = execute();

        }

        public function findOne(){
            $sql = "select * from places where id = :id_value";
            $statement = ($this->getConnection())->prepare($sql);
            $statement = bindValue(":id_value", $id);
            $statement = execute();

            return $statement->fetch(PDO::FETCH_ASSOC);


        }

        public function updateOne($id, $data){

            $placeInDatabase = $this->findOne($id);

            $sql = "update places set 
                name = :name_value,
                contact = :contact_value,
                opening_hours = :opening_hours_value,
                description = :description_value,
                latitude = :latitude_value,
                longitude = :longitude_value
            where id = :id_value";

            $statement = bindValue(":name_value", isset($data->name) ? $data->name : $placeInDatabase['name']);
            $statement = bindValue(":contact_value", isset($data->contact) ? $data->contact : $placeInDatabase['contact']);
            $statement = bindValue(":opening_hours_value", isset($data->opening_hours) ? $data->opening_hours : $placeInDatabase['opening_hours']);
            $statement = bindValue(":description_value", isset($data->description) ? $data->description : $placeInDatabase['description']);
            $statement = bindValue(":latitude_value", isset($data->latitude) ? $data->latitude : $placeInDatabase['latitude']);
            $statement = bindValue(":longitude_value", isset($data->longitude) ? $data->longitude : $placeInDatabase['longitude']);

            $statement = ($this->getConnection())->prepare($sql);
            $statement = bindValue(":id_value", $id);



        }

        public function update(){
            $body = getBody();
            $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$id){
                responseError('ID não encontrado', 400);
            }

            $placeDAO = new PlaceDAO();
            $placeDAO = updateOne($id, $body);

            response(['message' => 'atualizado com sucesso'], 200);
        }

        public function getConnection() {
            return $this->connection;
        }
    }

?>