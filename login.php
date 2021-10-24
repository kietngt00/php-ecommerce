<?php
require_once('./config/database.php');
require_once('./models/session.php');

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  $email = $data["email"];
  $password = $data["password"];
  
  $db = dbconnect2();
  if(!$db) {
    throw new Exception("DB cannot be connected", -99);
  }
  $stmt = mysqli_prepare($db, "SELECT id, role FROM User WHERE email=? AND password=?");
  mysqli_stmt_bind_param($stmt, "ss", $email, $password);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  if(mysqli_stmt_num_rows($stmt)==0) {
    echo "Email or Password Not Correct!";
    die();
  }
  mysqli_stmt_bind_result($stmt, $userId, $role);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
  $ses = new Session;
  $ses->start($userId, $role);
  $result = array(
    "message" => "login",
    "success" => true,
    "session_id" => $ses->getId()
  );
  echo json_encode($result);
}