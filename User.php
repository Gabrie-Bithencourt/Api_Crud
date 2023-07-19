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

    public function getUsers(){

        $users = null;
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;

        try {

            if(!is_null($id)){
                $sql = "SELECT id,nome,email FROM users WHERE id = :id";
                $query = $this->conexao->prepare($sql);
                $query->bindParam(":id",$id);
    
            }else{
                $sql = "SELECT id,nome,email FROM users";
                $query = $this->conexao->prepare($sql);
                
            }

            if($query->execute()){
                $users = $query->fetchAll(PDO::FETCH_ASSOC);
                
                if(empty($users)){
                    $users = "Nenhum Usuario cadastrado!!";
                }

                http_response_code(200);
                return $users;

            }else{
                throw new Exception(500);
                
            }
          
        } catch (\PDOException $exception) {
                http_response_code(500);
                return $exception->getMessage();

        } catch (\Exception $exception) {
                http_response_code(500);
                return $exception->getMessage();

        } finally {
            $this->conexao = null;
        }

    }

    function deleteUsers(){

        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;

        try {
            
            if(!is_null($id) && is_numeric($id)){

                $sql = "DELETE FROM users WHERE id = :id";
                $query = $this->conexao->prepare($sql);
                $query->bindParam(':id',$id);

                if($query->execute()){
                    http_response_code(200);
                    return "Usuario Excluido com sucesso!!";

                }else{
                    throw new Exception(500);
                    
                }


            }else{
                http_response_code(400);   
                return "ID enviado nao existe!!";
            }



        } catch (\Exception $exception) {
                    return $exception->getMessage();

        } finally{
            $this->conexao = null;
        }



    }



}


?>