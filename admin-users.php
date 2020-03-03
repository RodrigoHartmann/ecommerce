<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app->get("/users/:iduser/delete", function($iduser){

  User::verifyLogin();
  $user = new User();
  $user->get((int)$iduser);
  $user->delete();
  header("Location: /users/");
  exit;
});

$app->get("/users/", function(){ //Rota para o usuário cadastrado logado no sistema.

  User::verifyLogin();
  $users = User::listAll();
  $page = new PageAdmin();
  $page->setTpl("users", array(
   "users"=>$users
 ));
});

$app->get("/users/create", function(){ //Rota para criar o usuário

  User::verifyLogin();
  $page = new PageAdmin();
  $page->setTpl("users-create");
});

$app->post("/users/create", function () { //Rota para salvar o usuário

  User::verifyLogin();
  $user = new User();
  $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
  $user->setData($_POST);
  $user->save();
  header("location: /users/");
  exit;
});

$app->get("/users/:iduser", function($iduser){ //Rota para editar o usuário.

  User::verifyLogin();
  $user = new User();
  $user->get((int)$iduser);
  $page = new PageAdmin();
  $page->setTpl("users-update", array(
    "user"=>$user->getValues()
  ));
});

$app->post("/users/:iduser", function($iduser){ //Rota para slavar no banco a edição do usuário
 User::verifyLogin();
 $user = new User();
 $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 $user->get((int)$iduser);
 $user->setData($_POST);
 $user->update();
 header("Location: /users/");
 exit;
});


?>