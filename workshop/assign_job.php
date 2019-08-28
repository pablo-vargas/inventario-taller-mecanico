<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_POST['id'];

  $price = $_POST['price'];
  $detail = $_POST['detail'];
  $add_advance = "CALL asignar_trabajo('$detail',$id_vehicle,$price)";
  $ejecutar = $conexion->query($add_advance);
  header("Location: record_workshop.php?id=$id_vehicle");