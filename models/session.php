<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/php-ecommerce/common/constant.php');

class Session {
  function start($userId, $role) {
    foreach($_COOKIE as $name=>$value) {
      setcookie($name,"",1);
    }
    session_start();
    $_SESSION["userId"] = $userId;
    $_SESSION["role"] = $role;
    return true;
  }

  function getUser(){
    if(session_id() == '' || !isset($_SESSION["userId"])) {
      return false;
    }
    return $_SESSION["userId"];
  }

  function getRole(){
    if(session_id() == '' || !isset($_SESSION["role"])) {
      return false;
    }
    return $_SESSION["role"];
  }

  /** Get Session Id */
  function getId(){
    if(session_id() == '') {
      return false;
    }
    return session_id();
  }

  function end() {
    unset($_SESSION['userId']);
    unset($_SESSION['role']);
    session_unset();
    session_destroy();
  }

  /** Authentication */
  public static function authenticate(){
    if(!isset($_SESSION["userId"]) || !isset($_SESSION["role"])){
      echo "NEED_AUTHENTICATION";
      die();
    }
  }

  /** Admin Authorization */
  public static function AdminGuard() {
    if ($_SESSION["role"] != UserRole::admin) {
      echo "UNAUTHORIZED_ADMIN_ROLE";
      die();
    }
  }
}