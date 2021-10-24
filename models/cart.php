<?php

require_once "../config/database.php";

class Cart {
  private ?int $id;
  private ?int $userId;
  private ?int $productId;
  private ?int $amount;

  public function __construct($userId=null, $productId=null, $amount=null)
  {
    $this->userId = $userId;
    $this->productId = $productId;
    $this->amount = $amount;
  }

  public function get() {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, 
    "SELECT p.title, p.icon, p.price, c.amount
    FROM Cart as c
    RIGHT JOIN Product as p
    ON c.productId = p.id
    WHERE c.userId = ? "
    );
    mysqli_stmt_bind_param($stmt, "i", $this->userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $title, $icon, $price, $amount);
    $result = array();
    while(mysqli_stmt_fetch($stmt)) {
      $temp = array(
        "title" => $title,
        "icon" => $icon,
        "price" => $price,
        "amount" => $amount,
      );
      array_push($result,$temp);
    }
    mysqli_stmt_close($stmt);
    return $result;
  }

  public function checkCreated() {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db,"SELECT * FROM Cart WHERE userId=? AND productId=?");
    mysqli_stmt_bind_param($stmt, "ii", $this->userId, $this->productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) == 0){
      mysqli_stmt_close($stmt);
      return false;
    }
    mysqli_stmt_close($stmt);
    return true;
  }

  public function create() {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "INSERT INTO Cart (userId, productId) VALUE (?,?)");
    mysqli_stmt_bind_param($stmt, "ii", $this->userId, $this->productId);
    mysqli_stmt_execute($stmt);
    $this->id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);
    if(!$this->id) {
      return false;
    }
    return true;
  }

  public function increaseAmount($amount) {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "UPDATE Cart SET amount=amount+? WHERE userId=? AND productId=?");
    mysqli_stmt_bind_param($stmt, "iii", $amount, $this->userId, $this->productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }

  public function editAmount(){
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    if(!$this->checkCreated()){
      return false;
    }
    $stmt = mysqli_prepare($db, "UPDATE Cart SET amount=? WHERE userId=? AND productId=?");
    mysqli_stmt_bind_param($stmt, "iii", $this->amount, $this->userId, $this->productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }
  
  public function removeProduct(){
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "DELETE FROM Cart WHERE userId=? AND  productId=?");
    mysqli_stmt_bind_param($stmt, "ii", $this->userId, $this->productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }
}