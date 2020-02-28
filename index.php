<?php
session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

$app->get("/users/", function(){

    User::verifyLogin();
    $users = User::listAll();
    $page = new PageAdmin();
    $page->setTpl("users", array(
     "users"=>$users
    ));
});

$app->get("/users/create", function(){

    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("users-create");
});

$app->get("/users/:iduser", function($iduser){

    User::verifyLogin();
    
    $user = new User();
    $user->get((int)$iduser);
    $page = new PageAdmin();
    $page->setTpl("users-update", array(
    "user"=>$user->getValues()
    ));
});

$app->post("/users/create", function () {

    User::verifyLogin();

    $user = new User();
    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
    $_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [
        "cost"=>12
    ]);
    $user->setData($_POST);
    $user->save();
    header("Location: /users");
    exit;
});


$app->post("/users/:iduser", function($iduser){

    User::verifyLogin();
    $user = new User();
    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
    $user->get((int)$iduser);
    $user->setData($_POST);
    $user->update();
    header("localtion: /users");
    exit;    
});

$app->run();

 ?>