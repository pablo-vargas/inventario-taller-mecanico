<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['id'];
  $amount_payment = $_GET['amount'];


  $add_advance = "UPDATE vehicle_entry SET descuento_inventario=$amount_payment WHERE id=$id_vehicle";
  $ejecutar = $conexion->query($add_advance);
  header("Location: record_workshop.php?id=$id_vehicle");