<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_entry = $_GET['id'];
  $id = $_GET['idp'];
  $add_product = "CALL remove_entry_product($id)";
  $ejecutar = $conexion->query($add_product);
  header("Location: agregar_product.php?id=$id_entry");