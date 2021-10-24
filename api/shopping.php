<?php
header('Content-Type: application/json');

require_once("../models/cart.php");

// Handel POST request
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  $cart = new Cart($data["userId"], $data["productId"]);
  $result = array(
    "message"=>"buy_product" 
  );
  if(!$cart->checkCreated()){
    $success = $cart->create();
    $result["success"] = $success;
  } else {
    $success = $cart->increaseAmount(1);
    $result["success"] = $success;
  }
  echo json_encode($result);
}

// Handel GET request
else if($_SERVER["REQUEST_METHOD"] == "GET"){
  if(!isset($_GET["userId"])) {
    die();
  }
  $cart = new Cart($_GET["userId"]);
  $result = array(
    "message" => "get_cart",
    "userId" => $_GET["userId"],
    "data" => $cart->get()
  );
  echo json_encode($result);
}

// Handel PUT request
else if($_SERVER["REQUEST_METHOD"] == "PUT"){
  $data = json_decode(file_get_contents("php://input"), true);
  $cart = new Cart($data["userId"], $data["productId"], $data["amount"]);
  $result = array(
    "message" => "edit_amount",
    "success" => $cart->editAmount()
  );
  echo json_encode($result);
}

// Handel DELETE request
else if($_SERVER["REQUEST_METHOD"] == "DELETE") {
  $data = json_decode(file_get_contents("php://input"), true); 
  $cart = new Cart($data["userId"], $data["productId"]);
  $result = array(
    "message" => "remove_product",
    "success" => $cart->removeProduct()
  );
  echo json_encode($result);
}

else {
  echo "NOT_SUPPORT_METHOD";
}