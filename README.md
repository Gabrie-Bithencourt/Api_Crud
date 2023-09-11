# Api_Crud Restful
Criando uma cadastro de usuario usando a arquitetura RESTFul. Meu intuito e colocar em pratica meus estudos sobre API. Criei endpoinst para todas as funcionalidades de um crudÇ insert, delete, update, select

#Endpoints:

[ POST ]: caminho_ate_pasta_local/users -> Adiciona um novo pacote. 

 {
 
    "nome": "string",
    "email": "string",
    "senha": "string"
   
 } 
 
 Campos Obrigatórios * [" email ", " senha "]

 [ PUT ]: caminho_ate_pasta_local/users/id?=1 -> Atualiza todos os dados de um pacote.

 {
 
    "nome": "string",
    "email": "string",
    "senha": "string"
   
 } 
 
  Campos Obrigatórios * [" email ", " senha "] -> Os demais campos que não forem definidos na requisição serão atualizados para null.
   
 [ PATCH ]: caminho_ate_pasta_local/users/id?=1 -> Atualiza um ou mais dados de um pacote.

 {
 
    "nome": "string",
    "email": "string",
    "senha": "string"
   
 } 
 
  -> Os campos que não forem inseridos não serão atualizados.
     
 [ GET ]: caminho_ate_pasta_local/users -> Busca todos os pacotes.
 
 [ GET ]: caminho_ate_pasta_local/users/id?=1 -> Busca pacote especifico.
 
 [ DELETE ]: caminho_ate_pasta_local/users/id?=1 -> Deleta pacote especifico.
