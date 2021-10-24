<?php
require_once( '../config/database.php');

class Product
{
  private ?int $id;
  private ?string $title;
  private ?string $description;
  private ?string $icon;
  private ?float $price;
  private ?int $quantity;

  public function __construct($id = null, $title = null, $description = null, $icon = null, $price = null, $quantity = null)
  {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->icon = $icon;
    $this->price = $price;
    $this->quantity = $quantity;
  }

  /** List all products */
  public function list()
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "SELECT * FROM Product");
    mysqli_stmt_execute($stmt);
    $data = $stmt->get_result();
    $result = array();
    while ($row=$data->fetch_assoc()) {
      array_push($result, $row);
    }
    mysqli_stmt_close($stmt);
    return $result;
  }

  /** Get 1 product */
  public function get(int $id)
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "SELECT * FROM Product WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 0) {
      mysqli_stmt_close($stmt);
      throw new Exception("NOT_FOUND_PRODUCT", -1);
    }
    mysqli_stmt_bind_result($stmt, $this->id, $this->title, $this->description, $this->icon, $this->price, $this->quantity);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $result = array(
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,
      'icon' => $this->icon,
      'price' => $this->price,
      'quantity' => $this->quantity
    );
    return $result;
  }

  /** Create 1 product */
  public function create()
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare(
      $db,
      "INSERT INTO Product 
      (title, description, icon, price, quantity) 
      VALUE (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "sssdi", $this->title, $this->description, $this->icon, $this->price, $this->quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $this->id = mysqli_stmt_insert_id($stmt);
    mysqli_stmt_close($stmt);
    if (!$this->id) {
      return false;
    }
    return true;
  }

  /** Update 1 product */
  public function update()
  {
    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare(
      $db,
      "UPDATE Product
      SET 
        title=IF(? IS NULL, title, ?),
        description=IF(? IS NULL, description, ?),
        icon=IF(? IS NULL, icon, ?),
        price=IF(? IS NULL, price, ?),
        quantity=IF(? IS NULL, quantity, ?)
      WHERE id=?"
    );
    mysqli_stmt_bind_param(
      $stmt,
      "ssssssddiii",
      $this->title,
      $this->title,
      $this->description,
      $this->description,
      $this->icon,
      $this->icon,
      $this->price,
      $this->price,
      $this->quantity,
      $this->quantity,
      $this->id
    );
    mysqli_stmt_execute($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }

  /** Delete 1 product */
  public function delete() {
    // Delete from user cart: auto because ON DELETE CASCADE

    $db = dbconnect2();
    if (!$db) {
      throw new Exception("DB cannot be connected", -99);
    }
    $stmt = mysqli_prepare($db, "DELETE FROM Product WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $this->id);
    mysqli_stmt_execute($stmt);
    $success = !!mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $success;
  }
}
