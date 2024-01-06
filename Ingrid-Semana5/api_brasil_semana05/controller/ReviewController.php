<?php 

    require_once '../utils.php';
    require_once '../models/Review.php';
    require_once '../models/ReviewDAO.php';

    class ReviewController {
        
        public function create(){
        $body = getBody();

        $blacklist = ['polimorfismo', 'herança', 'abstração', 'encapsulamento'];


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

        $reviewDAO = new ReviewDAO($place_id);
        $result = $reviewDAO->findMany();

        response($result, 201);
    }
    }


?>