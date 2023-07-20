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
        'DELETE' => 'deleteUser',
        'PUT' => 'putUsers',
        'PATCH' => 'patchUsers'
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
    http_response_code(404);
    echo "Endpoint não encontrado.";
    return;
}


function createUser(){
    // Parametro true no json_encode para o conteudo ser um array associativo
    // ao inves de um objeto //
    $campos_required = ['email','senha'];
    $data = file_get_contents('php://input');

    if(empty($data) || is_null($data)){
        http_response_code(400);
        return "os campos [".implode(" - ",$campos_required)."] sao obrigatorios!";
    }

    $dados_request = json_decode($data,true);
    $indices =  array_keys($dados_request);

    $dados_user = [];
    $falta_info = [];

    for ($i=0; $i < count($indices); $i++) {
        $dados_user[$indices[$i]] = trim($dados_request[$indices[$i]]);
    }

    $falta_info = array_filter($campos_required,function($required)  use ($indices,$dados_user){
        return !in_array($required,$indices) || in_array($required,$indices) && empty($dados_user[$required]);

    });

    if(!empty($falta_info)){
        http_response_code(400);
        return "os campos [".implode(" - ",$campos_required)."] sao obrigatorios!";
    }

    // Criando  User //
    $user = new User();
    $response = $user->createUser($dados_user);
    return $response;

}

function getUsers(){

    $user = new User();
    $response = $user->getUsers();

    return $response;
}

function deleteUser(){

    $user = new User();
    $response = $user->deleteUsers();
    return $response;

}

function putUsers(){

    $data = file_get_contents('php://input');

    if(empty($data) || is_null($data)){
        http_response_code(400);
        return "Necessario informar os campos e os valores a serem atualizados!!";
    }

    $data = json_decode($data, true);

    $dados_user_update = [];
    $dados_user_update['email'] = isset($data['email']) ? trim($data['email']) : "";
    $dados_user_update['nome'] = isset($data['nome']) ? trim($data['nome']) : "";
    $dados_user_update['senha'] = isset($data['senha']) ? md5(trim($data['senha'])) : "";


    $user = new User();
    $response = $user->putUsers($dados_user_update);

    return $response;

}

function patchUsers(){

    $data = file_get_contents('php://input');

    if(empty($data) || is_null($data)){
        http_response_code(400);
        return "Necessario informar ao menos um campo para os valores serem atualizados!!";
    }

    $data = json_decode($data, true);
    $campos_update = array_keys($data);

    $campos = ['nome','email','senha'];
    $dados_update = [];

    for ($i=0; $i < count($campos_update) ; $i++) { 

        if(in_array($campos_update[$i],$campos)){
            if($campos_update[$i] == "email" || $campos_update[$i] == "nome"){
                $dados_update[$campos_update[$i]] = "'".$data[$campos_update[$i]]."'";
            }else{
                $dados_update[$campos_update[$i]] = "'".md5($data[$campos_update[$i]])."'";
            }

        }
        
    }

    if(empty($dados_update) || is_null($dados_update)){
        http_response_code(400);
        return "Campos dos usuarios sao: [".implode(" - ",$campos)."]";
    }

    $user = new User();
    $response = $user->patchUsers($dados_update);

    return $response;


}


?>