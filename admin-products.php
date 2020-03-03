<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;

$app->get("/products", function(){

	User::verifyLogin();
	$products = Product::listAll();
	$page = new PageAdmin();
	$page->setTpl("products",[
		"products"=>$products
	]);
});	

$app->get("/products/create", function(){

	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("products-create");
});

$app->post("/products/create", function(){

	User::verifyLogin();
	$product = new Product();
	$product->setData($_POST);
	$product->save();
	header("Location: /products");
	exit;
});

$app->get("/products/:idproduct", function($idproduct){
 
 User::verifyLogin();
 $product = new Product();
 $product->get((int)$idproduct);
 $page = new PageAdmin();
 $page->setTpl("products-update", [
 	'product'=>$product->getValues()
 ]);
});

$app->post("/products/:idproduct", function($idproduct){
 
 User::verifyLogin();
 $product = new Product();
 $product->get((int)$idproduct);
 $product->setData($_POST);
 $product->save();
 $product->setPhoto($_FILES["file"]);
 header('Location: /products');
 exit;
});

$app->get("/products/:idproduct/delete", function($idproduct){
 
 User::verifyLogin();
 $product = new Product();
 $product->get((int)$idproduct);
 $product->delete();
 header("Location: /products");
 exit;
});

?>