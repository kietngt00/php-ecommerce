<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once("../models/product.php");
require_once("../models/session.php");

session_start();

// Handel GET request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $product = new Product();
  // Get all
  if (!isset($_GET['id'])) {
    try {
      $result = array(
        "message" => 'list_products',
        'data' => $product->list()
      );
      echo json_encode($result);
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
    }
  }
  // Get 1
  else {
    $id = $_GET['id'];
    try {
      $data = $product->get($id);
      $result = array(
        "message" => 'get_product',
        'data' => $data,
      );
      echo json_encode($result);
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
    }
  }
}

// Handel POST request
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  Session::authenticate();
  Session::AdminGuard();
  $data = json_decode(file_get_contents("php://input"));
  $product = new Product(null,$data->title, $data->description, $data->icon, $data->price, $data->quantity);
  try {
    $result = array(
      "message" => 'create_product',
      'success' => $product->create(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}

else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
  Session::authenticate();
  Session::AdminGuard();
  $data = json_decode(file_get_contents("php://input"), true);
  $product = new Product(
    $_GET["id"],
    $data["title"] ?? null,
    $data["description"] ?? null,
    $data["icon"] ?? null,
    $data["price"] ?? null,
    $data["quantity"] ?? null
  );
  try {
    $result = array(
      "message" => 'update_product',
      'id' => $_GET["id"],
      'success' => $product->update(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}


else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
  Session::authenticate();
  Session::AdminGuard();
  if (!isset($_GET['id'])) {
    echo json_encode(array(
      "message" => 'delete_product',
      'id' => null,
      'success' => false,
    ));
    die();
  }

  $product = new Product( $_GET["id"]);
  try {
    $result = array(
      "message" => 'delete_product',
      'id' => $_GET["id"],
      'success' => $product->delete(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}

else {
  echo "NOT_SUPPORT_METHOD";
}
