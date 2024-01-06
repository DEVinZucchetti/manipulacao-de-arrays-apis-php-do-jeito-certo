<?php

    class ReviewDAO {
        private $connection;

        public function __construct() {
            $this->connection = new PDO("pgsql:host=localhost; dbname:api_places", "admin", "admin");
        }


        public function insert(Review $review){
            try{
                $sql = "insert into reviews 
                            (
                                place_id,
                                name,
                                email,
                                stars,
                                status,
                            )
                            values
                            (
                                :place_id_value,
                                :name_value,
                                :email_value,
                                :stars_value,
                                :status_value,
                            );
                ";

                $statement = ($this->getConnection())->prepare($sql);

                $statement = bindValue(":place_id_value", $review->getPlaceId());
                $statement = bindValue(":name_value", $review->getName());
                $statement = bindValue(":email_value", $review->getEmail());
                $statement = bindValue(":stars_value", $review->getStars());
                $statement = bindValue(":status_value", $review->getStatus());

                $statement = execute();

                return ['success' => true];} catch (PDOException $error){
                    debug($error->getMessage());
                    return ['success' => false];
                }
            }

                public function findMany() {
                    $sql = " select * from reviews, order by name";
        
                    $statement = ($this->getConnection())->prepare($sql);
        
                    $statement = execute();
        
                    return $statement-> fetchAll(PDO::FETCH_ASSOC);
                }
        
                public function deleteOne($id){
                    $sql = "delete from reviews where id = :id_value";
                    $statement = ($this->getConnection())->prepare($sql);
                    $statement = bindValue(":id_value", $id);
                    $statement = execute();
        
                }
        
                public function findOne($id){
                    $sql = "select * from reviews where id = :id_value";
                    $statement = ($this->getConnection())->prepare($sql);
                    $statement = bindValue(":id_value", $id);
                    $statement = execute();
        
                    return $statement->fetch(PDO::FETCH_ASSOC);
        
        
                }
        
                public function updateOne($id, $data){
        
                    $placeInDatabase = $this->findOne($id);
        
                    $sql = "update reviews set 
                        place_id = :place_id_value,
                        name = :name_value,
                        email = :email_value,
                        stars = :stars_value,
                        status = :status_value,
                    where id = :id_value";
        
                    $statement = bindValue(":status_value", isset($data->status) ? $data->status : $placeInDatabase['status']);
        
                    $statement = ($this->getConnection())->prepare($sql);
                    $statement = bindValue(":status_value", $data->status);
                    $statement = bindValue(":id_value", $id);
        
        
        
                }
        
                public function update(){
                    $body = getBody();
                    $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        
                    if (!$id){
                        responseError('ID não encontrado', 400);
                    }
        
                    $reviewDAO = new ReviewDAO();
                    $reviewDAO = updateOne($id, $data);
        
                    response(['message' => 'atualizado com sucesso'], 200);
                }
        
                public function getConnection() {
                    return $this->connection;
                }

        }
?>