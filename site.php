<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app->get('/', function() {

  $page = new Page();
  $page->setTpl("index");
});


?>