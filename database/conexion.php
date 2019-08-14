<?php
    include("config.php");

    $conexion =  new mysqli($server,$user,$password,$bd);
    if(mysqli_connect_errno()){
        echo"Failed Connected".mysqli_connect_errno();
        exit();
    }