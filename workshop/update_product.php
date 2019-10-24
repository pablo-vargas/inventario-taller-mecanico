<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['idv'];
  $cant = $_GET['amount'];
  $price = $_GET['price'];
  $id = $_GET['id'];
  $name = $_GET['name'];
  $sql = "CALL update_product_vehicle($id,$price,$cant,'$name')";
  $ejecutar = $conexion->query($sql);
  header("Location: record_workshop.php?id=$id_vehicle");