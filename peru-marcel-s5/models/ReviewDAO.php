<?php
require_once 'Database.php';

class ReviewDAO extends Database
{
    
    public function insert(Review $review)
    {
        try {
            $sql = "insert into reviews
                        (
                            place_id,
                            name,
                            email,
                            stars
                        )
                        values
                        (
                            :place_id_value,
                            :name_value,
                            :email_value,
                            :stars_value
                        );
        ";

            $statement = ($this->getConnection())->prepare($sql);

            $statement->bindValue(":place_id_value", $review->getPlaceId());
            $statement->bindValue(":name_value", $review->getName());
            $statement->bindValue(":email_value", $review->getEmail());
            $statement->bindValue(":stars_value", $review->getStars());

            $statement->execute();

            return ['success' => true];
        } catch (PDOException $error) {
            debug($error->getMessage());
            return ['success' => false];
        }
    }

    public function findMany()
    {
        $sql = "select * from reviews order by name";

        $statement = ($this->getConnection())->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($id)
    {
        $sql = "SELECT * from reviews where id = :id_value";

        $statement = ($this->getConnection())->prepare($sql);
        $statement->bindValue(":id_value", $id);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "delete from reviews where id = :id_value";

        $statement = ($this->getConnection())->prepare($sql);
        $statement->bindValue(":id_value", $id);
        
        $statement->execute();
    }

    public function update($id, $status) {
        $sql = "UPDATE reviews SET status = :status WHERE id = :id_value";

        $statement = ($this->getConnection())->prepare($sql);
        $statement->bindValue(":status", $status);
        $statement->bindValue(":id_value", $id);
        $statement->execute();
    }

}