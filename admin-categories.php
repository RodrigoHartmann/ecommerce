<?php 

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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

$app->get("/categories", function (){
  User::verifyLogin();
  $categories = Category::listAll();  
  $page = new PageAdmin();
  $page->setTpl("categories", [
   'categories'=>$categories
 ]);
});
?>