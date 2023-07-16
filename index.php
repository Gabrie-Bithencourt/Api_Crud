<?php
include('User.php');

// Pega o verbo HTTP //
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


// Remova a parte da URI após o ponto de interrogação, se houver
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

$uri = str_replace($_SERVER['SCRIPT_NAME'],"",$uri);
$uri = trim($uri, '/');


// Defina os endpoints da API e suas manipulações correspondentes
$endpoints = array(
    'users' => array(
        'GET' => 'getUsers',
        'POST' => 'createUser',
    )
);

// Verifique se o endpoint e o método estão definidos
if (isset($endpoints[$uri]) && isset($endpoints[$uri][$method])) {
    // Chame a função correspondente ao endpoint e método
    $functionName = $endpoints[$uri][$method];
    $response = call_user_func($functionName);
    echo json_encode($response);
} else {
    // Endpoint não encontrado
    header("HTTP/1.1 404 Not Found");
    echo "Endpoint não encontrado.";
}


function createUser(){
    // Parametro true no json_encode para o conteudo ser um array associativo
    // ao inves de um objeto //
    $campos_required = ['email','senha'];
    $dados_request = json_decode(file_get_contents('php://input'),true);
    $indices = array_keys($dados_request);
    $dados_user = [];
    $falta_info = [];

    for ($i=0; $i < count($indices); $i++) {
        $dados_user[$indices[$i]] = trim($dados_request[$indices[$i]]);
    }

    $falta_info = array_filter($campos_required,function($required)  use ($indices,$dados_user){
        return !in_array($required,$indices) || in_array($required,$indices) && empty($dados_user[$required]);

    });

    if(!empty($falta_info)){
        return "Erro - Falta info 400 dos campos: ".implode(" - ",$falta_info);
    }

    // Criando  User //
    $user = new User();
    $response = $user->createUser($dados_user);
    return $response;

}

function getUsers(){
    $conexao  = conectar();

    $sql = "SELECT * FROM users";
    $inst = $conexao->query($sql);
    $resultado = $inst->fetchAll();

    return $resultado;
}



?>