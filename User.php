<?php
require_once 'conexao.php';

class User {
    private $conexao;

    public function __construct(){
        $this->conexao = conectar();
    }

    public function createUser($dados_user){

        $nome  = isset($dados_user['nome']) ? $dados_user['nome'] : "";
        $email = isset($dados_user['email']) ? $dados_user['email'] : "";
        $senha = isset($dados_user['senha']) ? md5($dados_user['senha']) : "";

        try {

            $sql = "INSERT INTO users (nome,email,senha) VALUES (:nome,:email,:senha)";
            $query = $this->conexao->prepare($sql);
            $query->bindParam(":nome", $nome);
            $query->bindParam(":email", $email);
            $query->bindParam(":senha", $senha);

            if($query->execute()){
                $id_user_inserido = $this->conexao->lastInsertId();
                $query = null;
                return "Usuario Criado id: ".$id_user_inserido;

            }else{
                throw new Exception(500);
                $query = null;
            }


        } catch (\PDOException $exception) {
                return $exception->getMessage();
      
        } catch (\Exception $exception) {
                return $exception->getMenssage();

        } finally {
            $this->conexao = null;
        }

    }



}


?>