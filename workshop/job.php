<?php
  require('../fpdf/fpdf.php');
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_vehicle = $_GET['id'];
  $result_product = $conexion->query("SELECT C.name_client AS NCliente,
                                        C.carnet_identidad AS CCliente,
                                        C.telefono AS CTelefono,
                                        A.nombre AS NAsist,
                                        V.placa AS VPlaca,
                                        V.marca AS VMarca,
                                        V.modelo AS VModelo,
                                        V.color AS VColor,
                                        VI.fecha_ingreso AS FI,
                                        VI.fecha_salida AS FS,
                                        VI.costo_inventario AS CI,
                                        VI.costo_trabajo AS CT,
                                        VI.tipo_trabajo AS TT,
                                        VI.descuento_inventario AS DI,
                                        VI.descuento_trabajo AS DT,
                                        VI.adelantos AS AJ,
                                        VI.adelantos_inventario AS AI,
                                        (VI.costo_inventario-(VI.adelantos_inventario+VI.descuento_inventario)) AS DDI,
                                        (VI.costo_trabajo-(VI.adelantos+VI.descuento_trabajo)) AS DDT
                                         
                            FROM vehicle_entry VI 
                            INNER JOIN clients C ON  VI.carnet_cliente = C.carnet_identidad 
                            INNER JOIN assistant A ON VI.id_assistant=A.id
                            INNER JOIN vehicle V ON VI.placa=V.placa
                            WHERE VI.id=$id_vehicle");
    $row_product = $result_product->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="../design/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="../design/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="../design/css/sb-admin.css" rel="stylesheet">
    <link href="../design/datatables/dataTables.bootstrap4.css" rel="stylesheet">
</head>
<body>
    <div class="card" >
        <div class="card-body">
            <h5 class="card-title text-center">Orden de Trabajo</h5>
            <p class="card-text">
                Taller Mecanico Condor <br>
                Fecha : <?php echo $row_product['FI'].'&nbsp;&nbsp;'?> <br>
                Cliente : <?php echo $row_product['NCliente'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?>CI : <?php echo $row_product['CCliente']?>
                <br> Datos de Vehiculo <br>
                Placa : <?php echo $row_product['VPlaca'].'&nbsp;&nbsp;'?> Marca : <?php echo $row_product['VMarca'].'&nbsp; &nbsp;';?>
                Modelo : <?php echo $row_product['VModelo'].'&nbsp;&nbsp;'?> Color : <?php echo $row_product['VColor'].'&nbsp;&nbsp;'?>
            </p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Trabajo</th>
                        <th scope="col">Precio</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql_vehicle =mysqli_query($conexion,"SELECT * 
                                FROM assign_work A INNER JOIN jobs J ON A.id_job= J.id WHERE id_v=$id_vehicle");
                            $indice=1;
                            while($vehicle= mysqli_fetch_array($sql_vehicle)){
                            
                                echo "<tr>";
                                    echo "<th scope='row'> ".$indice."</th>";
                                    echo "<td>".$vehicle['trabajo']."</td>";
                                    echo "<td>".$vehicle['price_job']."</td>";  
                                echo "</tr>";
                                $indice++;
                            }
                            echo "<tr>";
                                echo "<td colspan='2'>SUB TOTAL </td>";
                                echo "<td>".$row_product['CT']."</td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td colspan='2'>DESCUENTO </td>";
                                echo "<td>".$row_product['DT']."</td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td colspan='2'>TOTAL </td>";
                                echo "<td>".$row_product['DDT']."</td>";
                            echo "</tr>"; 
                             
                        ?>             
                    </tbody>
                </table>
        </div>
    </div>
</body>
</html>