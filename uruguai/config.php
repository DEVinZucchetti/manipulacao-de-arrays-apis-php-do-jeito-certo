<?php
define('FILE_COUNTRY', 'uruguai.txt');

// Aplicação retorna JSON:
header("Content-Type: application/json");
// Aceita requisições de todas origens:
header("Access-Control-Allow-Origin: *");
// Habilita todos métodos:
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Define cabeçalhos de acesso permitidos para lidar com solicitações de origens diferentes:
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
