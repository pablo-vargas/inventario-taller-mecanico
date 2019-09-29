<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_entry = $_GET['id_ent'];
  $id = $_GET['id'];
  $price = $_GET['price'];
  $cant = $_GET['cant'];
  $add_product = "CALL edit_entry_product($id,$cant,$price)";
  $ejecutar = $conexion->query($add_product);
  header("Location: agregar_product.php?id=$id_entry");