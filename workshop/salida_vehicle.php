<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_POST['id'];
  $fecha = $_POST['fecha'];


  $add_advance = "UPDATE vehicle_entry SET fecha_salida='$fecha' WHERE id=$id_vehicle";
  $ejecutar = $conexion->query($add_advance);
  header("Location: record_workshop.php?id=$id_vehicle");