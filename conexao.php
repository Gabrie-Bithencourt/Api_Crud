<?php
// Conexao em PDO // 
define('DB_HOST', 'localhost');
define('DB_NAME', 'crud_api');
define('DB_USER', 'root');
define('DB_PASS', '');


function conectar(){

    try {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
        $pdo = new PDO($dsn,DB_USER,DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (\PDOException $exception) {

        die("Erro na conexao". $exception->getMessage());
    }

}


?>