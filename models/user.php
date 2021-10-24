<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php-ecommerce/config/database.php");
require_once('../common/constant.php');

class User
{
  private ?int $id;
  private ?string $email;
  private ?string $password;
  private ?string $avatar;
  private ?string $phone;
  private ?string $name;
  private ?string $sex;
  private ?string $role;

  public function __construct($id = null, $email = null, $password = null, $avatar = null, $phone = null, $name = null, $sex = null)
  {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->name = $name;
    $this->phone = $phone;
    $this->avatar = $avatar;
    $this->sex = $sex;
  }

  /** List all user */
  public static function list()
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "SELECT * FROM User");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $email, $dump, $avatar, $phone, $name, $sex, $role);
    $result = array();
    while (mysqli_stmt_fetch($stmt)) {
      $temp = array(
        'id' => $id,
        'email' => $email,
        'name' => $name,
        'phone' => $phone,
        'avatar' => $avatar,
        'sex' => $sex,
        'role' => $role
      );
      array_push($result, $temp);
    }
    mysqli_stmt_close($stmt);
    return $result;
  }

  /** Get 1 user */
  public function get(int $id)
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "SELECT * FROM User WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 0) {
      mysqli_stmt_close($stmt);
      throw new Exception("NOT_FOUND_USER", -1);
    }
    mysqli_stmt_bind_result($stmt, $this->id, $this->email, $dump, $this->avatar, $this->phone, $this->name, $this->sex, $this->role);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $result = array(
      'id' => $this->id,
      'email' => $this->email,
      'name' => $this->name,
      'phone' => $this->phone,
      'avatar' => $this->avatar,
      'sex' => $this->sex,
      'role' => $this->role
    );
    return $result;
  }

  /** Create 1 user */
  public function create()
  { 
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare(
      $db,
      "INSERT INTO User 
      (email, password, avatar, phone, name, sex) 
      VALUE (?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssssss", $this->email, $this->password, $this->avatar, $this->phone, $this->name, $this->sex);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $this->id = mysqli_stmt_insert_id($stmt);
    if (!$this->id) {
      return false;
    }
    return true;
  }

  /** Update 1 user */
  public function update()
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare(
      $db,
      "UPDATE User
       SET 
         avatar=IF(? IS NULL, avatar, ?),
         phone=IF(? IS NULL, phone, ?),
         name=IF(? IS NULL, name, ?),
         sex=IF(? IS NULL, sex, ?)
       WHERE id=?"
    );
    mysqli_stmt_bind_param(
      $stmt,
      "ssssssssi",
      $this->avatar,
      $this->avatar,
      $this->phone,
      $this->phone,
      $this->name,
      $this->name,
      $this->sex,
      $this->sex,
      $this->id
    );
    mysqli_stmt_execute($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }

  /** Delete 1 user */
  public function delete() {
    // user cart is deleted automatically (on delete cascade)
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "DELETE FROM User WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $this->id);
    mysqli_stmt_execute($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }
}
