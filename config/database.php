<?php
  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_DATABASE', 'ecommerce');
  define('DB_PORT', '3308');

   /**
    * This function is for connecting MySQL Database in OOP
    */
   function dbconnect(){
       return new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_PORT);
   }

   /**
    * This dunction is for connecting MySQL in procedural interface
    */
   function dbconnect2(){
       return mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_PORT);
   }
?>

