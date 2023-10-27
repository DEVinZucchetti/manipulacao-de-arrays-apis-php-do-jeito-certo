<?php
// Obtém o corpo da requisição HTTP e o decodifica a partir de JSON
function getBody()
{
    return json_decode(file_get_contents("php://input"));
}
// Lê o conteúdo de um arquivo com o nome especificado e o decodifica a partir de JSON
function readFileContent($fileName)
{
    return json_decode(file_get_contents($fileName));
}
// Salva o conteúdo em um arquivo com o nome especificado após codificá-lo para JSON
function saveFileContent($fileName, $content)
{
    file_put_contents($fileName, json_encode($content, JSON_PRETTY_PRINT));
}

// Função de validação
function sanitizeString($value)
{
    return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
}

// Função de retorno de erro
function responseError($message, $status)
{
    http_response_code($status);
    echo json_encode(['error' => $message]);
    exit;
}

// Função para resposta
function response($response, $status)
{
    http_response_code($status);
    echo json_encode($response);
    exit;
}

function debug($content)
{
  echo '<pre>';
  echo var_dump($content);
  echo '</pre>';
}

function sanitizeInput($data, $property, $filterType, $isObject = true) {
    if($isObject) {
      return isset($data->$property) ? filter_var($data->$property, $filterType) : null;
    } else {
      return isset($data[$property]) ? filter_var($data[$property], $filterType) : null;
    }
   
  }