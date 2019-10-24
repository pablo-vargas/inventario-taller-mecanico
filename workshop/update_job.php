<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['idv'];
  
  $price = $_GET['price'];
  $id = $_GET['id'];
  $name = $_GET['name'];
  $sql = "CALL update_job_vehicle($id,$price,'$name')";
  $ejecutar = $conexion->query($sql);
  header("Location: record_workshop.php?id=$id_vehicle");