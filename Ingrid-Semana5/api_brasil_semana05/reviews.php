<?php 
    require_once 'config.php';
    require_once 'utils.php';
    
    $method = $_SERVER['REQUEST_METHOD'];

    $blacklist = ['polimorfismo', 'herança', 'abstração', 'encapsulamento'];

    if($method === 'POST') {

        $body = getBody();
        $place_id = sanitizeInput($body, 'place_id', FILTER_VALIDATE_INT);
        $name = sanitizeInput($body, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = sanitizeInput($body, 'email', FILTER_VALIDATE_EMAIL);
        $stars = sanitizeInput($body, 'stars', FILTER_VALIDATE_FLOAT);
        $status = sanitizeInput($body, 'status', FILTER_SANITIZE_SPECIAL_CHARS);


        if(!$place_id || !$name || !$email || !$stars || !$status) {
            responseError('Informação ausente. Revise sua requisição', 400);
        }

        if(strlen($name) > 200) {
            responseError('O texto ultrapassou o limite de caracteres', 400);
        }

        if($stars < 1 || $stars > 5) {
            responseError('As notas devem ser de 1 a 5', 400);
        }

        foreach($blacklist as $word) {
            if(str_contains(strtolower($name), $word)) {
                $name = str_replace($word, '[EDITADO PELO ADMIN]', $name);
            }
        }

        $review = new Review($place_id);

        $review->setName($name);
        $review->setEmail($email);
        $review->setStars($stars);
        $review->setStatus($status);

        $review->save();

        response(['message => Cadastrado com sucesso'], 201);

    } else if($method === 'GET') {
        $place_id = sanitizeInput($_GET, 'id', FILTER_VALIDATE_INT);

        if(!$place_id) {
            responseError('O ID do lugar é obrigatório', 400);
        }

        $review = new Review($place_id);
        
        response($review->list(), 200);

    } else if($method === 'PUT') {
        $review = new Review();
        $review->updateStatus($id, $status);

        response(['message' => 'Atualizado com sucesso'], 200);
        
    }

?>