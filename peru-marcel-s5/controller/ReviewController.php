<?php
require_once '../utils.php';
require_once '../models/Review.php';
require_once '../models/ReviewDAO.php';

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

        $reviewDAO = new ReviewDAO();
        $result = $reviewDAO->insert($review);
    
        if ($result['success'] === true) {
            response(["message" => "Cadastrado com sucesso"], 201);
        } else {
            responseError("Não foi possível realizar o cadastro", 400);
        }

    }

    public function list()
    {
        $reviewDAO = new ReviewDAO();
        $result = $reviewDAO->findMany();
        response($result, 200);
    }

    public function delete()
    {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) {
            responseError('ID ausente', 400);
        }

        $reviewDAO = new ReviewDAO();
        $reviewDAO->delete($id);

        response(['message' => 'Deletado com sucesso'], 204);
    }

    public function listOne()
    {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) {
            responseError('ID ausente', 400);
        }

        $reviewDAO = new ReviewDAO();
        $item = $reviewDAO->findOne($id);

        response($item, 200);
    }

    public function update() {
        $body = getBody();

        $id =  sanitizeInput($_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS, false);
        $status = sanitizeInput($body, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id) responseError('ID da avaliação ausente.', 400);
        if (!$status) responseError('Status ausente.', 400);

        $reviewDAO = new ReviewDAO();
        $reviewDAO->update($id, $status);
        response("Alteração realizada com sucesso.", 200);
    }
}
