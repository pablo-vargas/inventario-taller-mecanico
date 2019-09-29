<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_entry = $_GET['id'];
  $id_prod = $_GET['id_p'];
  $cant = $_GET['cant'];
  $add_product = "CALL add_entry_product($id_entry,$id_prod,$cant)";
  $ejecutar = $conexion->query($add_product);
  header("Location: agregar_product.php?id=$id_entry");