<?php
    
    class Review {
        private $id, $name, $email, $stars, $date, $status, $place_id;

        public function __construct($place_id = null)
        {
            $this->id = uniqid();
            $this->place_id = $place_id;
            $this->date = (new DateTime())->format('d/m/Y h:m');


        }

        public function save(){
            $data = [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'stars' => $this->getStars(),
                'date' => $this->getDate(),
                'place_id' => $this->getPlaceId()
            ];

            $allData = readFileContent('reviews.txt');
            array_push($allData, $data);
            saveFileContent('reviews.txt', $allData);
        }

        public function list() {
            $allData = readFileContent('reviews.txt');
            $filtered = array_filter($allData, function($review){
                return $review->place_id === $this->getPlaceId();
            });
            return $filtered;
        }

        public function updateStatus($id, $status){
            $allData = readFileContent('reviews.txt');

            foreach($allData as $position => $item) {
                if($item->$id === $id) {
                    $allData[$position]->status == $status;
                }
            }

            saveFileContent('reviews.txt', $allData);

        }

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }
        
        public function getStars()
        {
            return $this->stars;
        }

        public function setStars($stars)
        {
            $this->stars = $stars;

            return $this;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function setStatus($status)
        {
            $this->status = $status;

            return $this;
        }

        public function getPlaceId()
        {
            return $this->place_id;
        }

        public function setPlaceId($place_id)
        {
            $this->place_id = $place_id;

            return $this;
        }

    }

?>