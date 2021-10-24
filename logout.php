<?php

require_once ("./models/session.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
  session_start();
  $result = array(
    "message" => "logout",
    "success" => Session::end()
  );
  echo json_encode($result);
}

