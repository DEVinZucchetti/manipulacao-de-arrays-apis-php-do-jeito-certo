<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'models/Review.php';

$method = $_SERVER['REQUEST_METHOD'];

// Teste de palavras proibidas
$blacklist = ['polimorfismo',  'herança', 'abstração', 'encapsulamento'];

if ($method === 'POST') {
    $body = getBody();

    //Validando os campos
    $place_id = sanitizeInput($body, 'place_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $name = sanitizeInput($body, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = sanitizeInput($body, 'email', FILTER_VALIDATE_EMAIL);
    $stars = sanitizeInput($body, 'stars', FILTER_VALIDATE_FLOAT);

    // Verifica a ausência de informações
    if (!$place_id) responseError('Id do lugar ausente', 400);
    if (!$name) responseError('Descrição da avaliação ausente', 400);
    if (!$email) responseError('Email inválido', 400);
    if (!$stars) responseError('Quantidade de estrelas ausente', 400);

    // Erro se o nome tiver mais de 200 caracteres
    if (strlen($name) > 200) responseError('O texto ultrapassou o limite', 400);

    foreach ($blacklist as $word) {
        //A variável word contém todas as palavras do blacklist
        if (str_contains(strtolower($name), $word)) {
            // Deixa tudo minusculo e retorna um true ou false
            $name = str_ireplace($word, '😷', $name);
        }
    }

    /*Usaremos os métodos (funções que criamos para alterar as informações da classe */
    $review = new Review($place_id);
    $review->setName($name);
    $review->setEmail($email);
    $review->setStars($stars);
    $review->save();

    response(['message' => 'Cadastro com sucesso'], 201);
} else if ($method === 'GET') {

    $place_id = sanitizeInput($_GET,  'id', FILTER_SANITIZE_SPECIAL_CHARS, false);

    if (!$place_id) responseError('ID do lugar está ausente', 400);

    $reviews = new Review($place_id);

    response($reviews->list(), 200);
} else if ($method === "PUT") {
    $body = getBody();
    $id =  sanitizeInput($_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS, false);

    $status = sanitizeInput($body,  'status', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$status) {
        responseError('Status ausente', 400);
    }

    $review = new Review();
    $review->updateStatus($id, $status);

    response(['message' => 'Atualizado com sucesso'], 200);
}

/*Informações do projeto
1 - str_contains informa se dentro do texto possui uma palavra específica. */