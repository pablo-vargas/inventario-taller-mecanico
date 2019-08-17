<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['id'];
  $amount_payment = $_GET['amount'];
  $detail_advance = $_GET['detail'];

  $add_advance = "CALL add_advance_job($id_vehicle,$amount_payment,'$detail_advance')";
  $ejecutar = $conexion->query($add_advance);
  header("Location: record_workshop.php?id=$id_vehicle");