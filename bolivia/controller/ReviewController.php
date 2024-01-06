<?php

require_once 'utils.php';
require_once 'models/Review.php';

class ReviewController{
    public function create()
    {
        $blacklist = ['polimorfismo', 'herança', 'abstração', 'encapsulamento'];
        $body = getBody();

        $place_id = sanitizeInput($body, 'place_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = sanitizeInput($body, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = sanitizeInput($body, 'email', FILTER_VALIDATE_EMAIL);
        $stars = sanitizeInput($body, 'stars', FILTER_VALIDATE_FLOAT);

        if (!$place_id) responseError('Id da avaliação ausente', 400);
        if (!$name) responseError('Descricao da avaliação ausente', 400);
        if (!$email) responseError('Email ausente', 400);
        if (!$stars) responseError('Estrelas da avaliação ausente', 400);


        if (strlen($name) > 200) responseError('O texto ultrapassou o limite', 400);

        foreach ($blacklist as $word) {
            if (str_contains(strtolower($name), $word)) {
                $name = str_ireplace($word, '[EDITADO PELO ADMIN]', $name);
            }
        }

        $review = new Review($place_id);
        $review->setName($name);
        $review->setEmail($email);
        $review->setStars($stars);
        $review->save();

        response(['message' => 'Cadastrado com sucesso'], 201);
    }

    public function list()
    {
        $place_id =  sanitizeInput($_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS, false);
        if (!$place_id) responseError('ID do local ausente', 400);

        $reviews = new Review($place_id);
        response($reviews->list(), 200);
    }

    public function update()
    {
        $body = getBody();
        $id = sanitizeInput($_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS, false);

        $status = sanitizeInput($body, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$status) {
            responseError('Status ausente', 400);
        }

        $review = new Review();
        $review->updateStatus($id, $status);

        response(['message' => 'Atualizado com sucesso'], 200);
    }
}