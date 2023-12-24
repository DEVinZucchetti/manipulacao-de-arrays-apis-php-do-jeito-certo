<?php
require_once './utils.php';

class Review
{
    private $id;
    private $name;
    private $email;
    private $stars;
    private $date;
    private $status;
    private $place_id;

    public function __construct($place_id)
    {
        $this->id = uniqid();
        $this->place_id = $place_id;
        $this->date = (new DateTime())->format('d/m/Y h:i');
    
    }

    public function save()
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'stars' => $this->getStars(),
            'status' => $this->getStatus(),
            'place_id' => $this->getId(),
        ];

        $allData = readFileContent('reviews.txt');
        array_push($allData, $data);
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

    public function getPlace_id()
    {
        return $this->place_id;
    }

    public function setPlace_id($place_id)
    {
        $this->place_id = $place_id;

        return $this;
    }
}
