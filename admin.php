<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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


?>