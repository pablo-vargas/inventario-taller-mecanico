<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['id'];
  $cant = $_GET['amount'];
  $price = $_GET['price'];
  $prod = $_GET['prod'];
  $name = $_GET['name'];
  $add_advance = "CALL consumo_inv($prod,$id_vehicle,$price,$cant,'$name')";
  $ejecutar = $conexion->query($add_advance);
  header("Location: record_workshop.php?id=$id_vehicle");