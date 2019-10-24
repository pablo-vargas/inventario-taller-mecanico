<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id = $_GET['id'];
  $id_vehicle = $_GET['idv'];

  $sql = "CALL remove_product_vehicle($id)";
  $ejecutar = $conexion->query($sql);
  header("Location: record_workshop.php?id=$id_vehicle");