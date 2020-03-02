<?php
session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

  $page = new Page();
  $page->setTpl("index");
});

$app->get('/admin', function() {

  User::verifyLogin();
  $page = new PageAdmin();
  $page->setTpl("index");
});

$app->get('/login', function(){

	$page = new PageAdmin([
   "header"=>false,
   "footer"=>false
 ]);
	$page->setTpl("login");
});

$app->post('/admin/login', function(){

  User::login($_POST['login'], $_POST['password']);
  header("Location: /admin");
  exit;
});

$app->get('/logout', function(){

  User::logout();
  header("Location: /login");
  exit;
});

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

$app->get("/forgot", function(){

  $page = new PageAdmin([
   "header"=>false,
   "footer"=>false
 ]);
  $page->setTpl("forgot");
});

$app->post("/forgot", function(){

 $user = User::getforgot($_POST["email"]);
 header("Location: /forgot/sent");
 exit;
});

$app->get("/forgot/sent", function(){
  $page = new PageAdmin([
   "header"=>false,
   "footer"=>false
 ]);
  $page->setTpl("forgot-sent");
});

$app->get("/forgot/reset", function(){

 $user = User::validForgotDecrypt($_GET["code"]);
 $page = new PageAdmin([
   "header"=>false,
   "footer"=>false
 ]);
 $page->setTpl("forgot-reset", array(
  "name"=>$user["desperson"],
  "code"=>$_GET["code"]
));
});

$app->post("/forgot/reset", function(){
  $forgot = User::validForgotDecrypt($_GET["code"]);
  User::setForgotUsed($user["idcovery"]);
  $user = new User();
  $user->get((int)$forgot["iduser"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT,[
    "cost"=>12
  ]);
  $user->setPassword($password);
  $page = new PageAdmin([
    "header"=>false,
    "footer"=>false
  ]);
  $page->setTpl("fotgot-reset-sucess");
});

$app->get("/categories", function (){
  User::verifyLogin();
  $categories = Category::listAll();  
  $page = new PageAdmin();
  $page->setTpl("categories", [
   'categories'=>$categories
 ]);
});

$app->get("/categories/create", function (){
  User::verifyLogin();
  $page = new PageAdmin();
  $page->setTpl("categories-create");
});

$app->post("/categories/create", function (){
 User::verifyLogin();
 $category = new Category();
 $category->setData($_POST);
 $category->save();
 header("Location: /categories");
 exit;
});

$app->get("/categories/:idcategory/delete", function ($idcategory){
  User::verifyLogin();
  $category = new Category();
  $category->get((int)$idcategory);
  $category->delete();
  header("Location: /categories");
  exit;
});

$app->get("/categories/:idcategory", function ($idcategory){
  User::verifyLogin();
  $category = new Category();
  $category->get((int)$idcategory);
  $page = new PageAdmin();
  $page->setTpl("categories-update", [
    "category"=>$category->getValues()
  ]);
});

$app->get("/category/:idcategory", function ($idcategory){
  $category = new Category();
  $category->get((int)$idcategory);
  $page = new Page();
  $page->setTpl("category",[
  "category"=>$category->getValues()
  ]);
});

$app->post("/categories/:idcategory", function ($idcategory){
  User::verifyLogin();
  $category = new Category();
  $category->get((int)$idcategory);
  $category->setData($_POST);
  $category->save();
  header("Location: /categories");
  exit;
});
$app->run();

?>