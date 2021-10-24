<?php

header('Content-Type: application/json');

require_once('../models/user.php');
require_once('../common/constant.php');
require_once('../models/session.php');

session_start();
// Handel GET request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  Session::authenticate();
  // Get all
  if (!isset($_GET['single'])) {
    Session::AdminGuard();
    $result = array(
      "message" => 'list_users',
      'data' => User::list()
    );
    echo json_encode($result);
  }
  // Get 1
  else {
    $single = $_GET['single'];
    $user = new User();
    try {
      $result = array(
        "message" => 'get_user',
        'data' => $user->get($_SESSION["userId"])
      );
      echo json_encode($result);
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
    }
  }
}

// Handel POST request
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"));
  $user = new User(null,$data->email, $data->password, $data->avatar, $data->phone, $data->name, $data->sex);
  try {
    $result = array(
      "message" => 'create_user',
      'success' => $user->create(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}

// Handel PUT request
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
  Session::authenticate();
  $data = json_decode(file_get_contents("php://input"), true);
  $user = new User(
    $_SESSION["userId"],
    null,
    null,
    $data["avatar"] ?? null,
    $data["phone"] ?? null,
    $data["name"] ?? null,
    $data["sex"] ?? null,
  );
  try {
    $result = array(
      "message" => 'update_user',
      'id' => $_SESSION["userId"],
      'success' => $user->update(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}

// Handel DELETE request
else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
  Session::authenticate();
  Session::AdminGuard();
  if (!isset($_GET['id'])) {
    echo json_encode(array(
      "message" => 'delete_user',
      'id' => null,
      'success' => false,
    ));
    die();
  }
  $user = new User($_GET["id"]);
  try {
    $result = array(
      "message" => 'delete_user',
      'id' => $_GET["id"],
      'success' => $user->delete(),
    );
    echo json_encode($result);
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), " ", $e->getCode(), "\n";
  }
}

else {
  echo "NOT_SUPPORT_METHOD";
}