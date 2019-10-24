<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  $id_user = $_SESSION['id_user'];
  $sql = "SELECT name_user,id,permission_inventory,permission_taller FROM usuario WHERE id = '$id_user'";
  $resultado = $conexion->query($sql);
  $row = $resultado->fetch_assoc();

  $PERMTALLER = $row['permission_taller'];
  $PERMINV = $row['permission_inventory'];


  $id_vehicle = $_GET['id'];
  $result_product = $conexion->query("SELECT C.name_client AS NCliente,
                                        C.carnet_identidad AS CCliente,
                                        C.telefono AS CTelefono,
                                        A.nombre AS NAsist,
                                        V.placa AS VPlaca,
                                        V.marca AS VMarca,
                                        V.modelo AS VModelo,
                                        V.color AS VColor,
                                        date(VI.fecha_ingreso) AS FI,
                                        date(VI.fecha_salida) AS FS,
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
  $tipo_trabajo='';
  if($row_product['TT'] =='1'){
    $tipo_trabajo = 'publico';
  }
  if($row_product['TT'] =='2'){
    $tipo_trabajo = 'intermedio';
  }
  if($row_product['TT'] =='3'){
    $tipo_trabajo = 'con factura';
  }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehiculos en Taller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap core CSS-->
    <link href="../design/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="../design/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="../design/css/sb-admin.css" rel="stylesheet">
    <link href="../design/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    
    <script>
        function advance_payments_job(id,permiso){
            if(permiso=='NO'){
                alert('Ud. No esta Autorizado');
            }else{
                var amount=parseFloat(prompt('Ingrese el mondo a registrar como adelanto',0));
                var detail=prompt('Ingrese el detalle de adelanto'); 
                location.href ="advance_payments_job.php?id="+id+"&amount="+amount+"&detail="+detail;
            }
        }
        function descuento_job(id,permiso){
            if(permiso=='NO'){
                alert('Ud. No esta Autorizado');
            }else{
                var amount=parseFloat(prompt('Ingrese el descuento al total del costo de trabajo',"<?php echo $row_product['DT']?>"));
                location.href ="descuento_job.php?id="+id+"&amount="+amount;
            }
        }
        function advance_payments_inv(id,permiso){
            if(permiso=='NO'){
                alert('Ud. No esta Autorizado');
            }else{
                var amount=parseFloat(prompt('Ingrese el mondo a registrar como adelanto',0));
                var detail=prompt('Ingrese el detalle de adelanto'); 
                location.href ="advance_payments_inv.php?id="+id+"&amount="+amount+"&detail="+detail;
            }
        }
        function descuento_inv(id,permiso){
            if(permiso=='NO'){
                alert('Ud. No esta Autorizado');
            }else{
                var amount=parseFloat(prompt('Ingrese el descuento al total del costo de trabajo',"<?php echo $row_product['DI']?>"));
                location.href ="descuento_inv.php?id="+id+"&amount="+amount;
            }
        }

        function entrega_productos(id,permiso,precio,prod,nombre){
            if(permiso){
                var price = parseFloat(prompt('Verificar precio de producto',precio));
                var cantidad = parseFloat(prompt('Cantidad entrega de productos',1));
                var name = prompt('Nombre para registro',nombre);
                location.href ="consumo_inv.php?id="+id+"&amount="+cantidad+"&prod="+prod+"&price="+price+"&name="+name;
            }else{
                alert("Ud. No esta Autorizado");
            }
        }

        function remove_product(id,idv){
            location.href="remove_product.php?id="+id+"&idv="+idv;
        }
        function update_product(idv,id,precio,cantidad,nombre){
            var price = parseFloat(prompt('Verificar precio de producto',precio));
            var cantidad = parseFloat(prompt('Cantidad entrega de productos',cantidad));
            var name = prompt('Nombre para registro',nombre);
            location.href ="update_product.php?idv="+idv+"&amount="+cantidad+"&id="+id+"&price="+price+"&name="+name;
        }
        function remove_trabajo(id,idv){
            location.href="remove_job.php?id="+id+"&idv="+idv;
        }
        function update_job(idv,id,precio,detalle){
            var price = parseFloat(prompt('Verificar precio de producto',precio));
            var name = prompt('Nombre para registro',detalle);
            location.href ="update_job.php?idv="+idv+"&id="+id+"&price="+price+"&name="+name;
        }

        function asignar_trabajo(id,permiso,p1,p2,p3,tipo,trabajo){
            if(permiso){
                var price=0;
                if(tipo==1){
                    price= p1;
                }if(tipo==2){
                    price =p2;
                }
                if(tipo ==3){
                    price = p3;
                }

                var price_j = parseFloat(prompt('Verificar precio de trabajo',price));
                
                
                location.href ="assign_job.php?id="+id+"&price="+price_j+"&job="+trabajo;
            }else{
                alert("Ud. No esta Autorizado");
            }
        }

        

    </script>
</head>
<body  class="fixed-nav sticky-footer bg-dark" id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">    
        <a class="navbar-brand" href="../inventory.php">Tamecon</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
              <a class="nav-link" href="../inventory.php">
                  <i class="fa fa-fw fa-home"></i>
                  <span class="nav-link-text">Inicio</span>
              </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
              <a class="nav-link" href="sale/venta.php">
                  <i class="fa fa-fw fa-usd"></i>
                  <span class="nav-link-text">Vender</span>
                  <span class="badge badge-pill badge-primary">12 New</span>
              </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
              <a class="nav-link" href="sale/detail_sale.php">
                  <i class="fa fa-fw fa-table"></i>
                  <span class="nav-link-text">Detalle Ventas</span>
              </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
              <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
                  <i class="fa fa-database"></i>
                  <span class="nav-link-text">Inventario</span>
              </a>
            <ul class="sidenav-second-level collapse" id="collapseComponents">
                <li>
                <a href="navbar.html">Productos</a>
                </li>
                <li>
                <a href="cards.html">Entradas</a>
                </li>
                <li>
                <a href="cards.html">Detalle de Entradas</a>
                </li>
                
            </ul>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
                <i class="fa fa-fw fa-file"></i>
                <span class="nav-link-text">Catalogos</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseExamplePages">
                <li>
                <a href="../layout_assistant.php">Ayudantes</a>
                </li>
                <li>
                <a href="../layout_clients.php">Clientes</a>
                </li>
                <li>
                <a href="../layout_vehiculo.php">Vehiculos</a>
                </li>
                <li>
                <a href="blank.html">Proveedores</a>
                </li>
            </ul>
            </li>
            
            
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseAdmi" data-parent="#exampleAccordion">
                <i class="fa fa-fw fa-cog"></i>
                <span class="nav-link-text">Administracion</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseAdmi">
                <li>
                <a href="login.html">Usuarios</a>
                </li>
                </li>
            </ul>
            </li>

        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
            <a class="nav-link text-center" id="sidenavToggler">
                <i class="fa fa-fw fa-angle-left"></i>
            </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            
          <li class="nav-item">
            <a class="nav-link" >
              <i class="fa fa-fw fa-user"></i><?php echo utf8_decode($row['name_user']);?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-fw fa-sign-out"></i>Cerrar Sesión</a>
          </li>
        </ul>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container-fluid">
        
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="layout_workshop.php">Inicio / Taller</a>
            </li>
            <li class="breadcrumb-item active">Registro Vehiculo</li>
            </ol>
            <div class="col-xl-12 col-sm-6 mb-3 float-rigth">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#asignar_job">ASIGNAR TRABAJO</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#asignar_inv">CONSUMO INVENTARIO</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#salida">REGISTRO SALIDA DE VEHICULO</button>
                
            </div>

            <div class="card border-secondary mb-3">
                <div class="card-header"><?php echo $row_product['NCliente'];?> ----- CI: <?php echo $row_product['CCliente'];?> ----- TEL: <?php echo $row_product['CTelefono'];?></div>
                <div class="card-body text-secondary">
                    <h5 class="card-title"> Nro Registro : <?php echo $id_vehicle;?> </h5>
                <p class="card-text"> 
                    Placa Vehiculo : <?php echo $row_product['VPlaca'];?><br>
                    Marca : <?php echo $row_product['VMarca'];?><br> 
                   
                    Modelo : <?php echo $row_product['VModelo'];?> <br>
                    Color : <?php echo $row_product['VColor'];?> <br><br>
                    Asistente a Cargo : <?php echo $row_product['NAsist'];?><br><br>
                    Fecha de Ingreso del Vehiculo : <?php echo $row_product['FI'];?> <br>
                    Fecha de Salida del Vehiculo : <?php echo $row_product['FS'];?> <br>
                    
                    
                    Tipo de trabajo realizado : <?php echo $tipo_trabajo;?> <br><br>
                    Adelantos - Descuentos <br>
                    TRABAJO REALIZADO <br>
                    Costo de trabajo realizado : <?php echo $row_product['CT'];?> 
                    <button class="badge badge-success" 
                        data-toggle="modal" data-target="#editar_trabajo">
                            Editar trabajos
                    </button>
                        <br>
                    Adelantos de trabajo realizado : <?php echo $row_product['AJ'];?>
                        <button class="badge badge-primary" 
                            onClick="<?php echo "advance_payments_job($id_vehicle,'$PERMTALLER')";?>">
                                registrar adelanto de trabajo
                        </button>
                        <button  class="badge badge-secondary" 
                            data-toggle="modal" data-target="#historial_pagos_job">
                                Historial de pagos de trabajo
                        </button> <br>
                    Descuento de trabajo realizado : <?php echo $row_product['DT'];?>
                        <button  class="badge badge-danger" 
                            onClick="<?php echo "descuento_job($id_vehicle,'$PERMTALLER')";?>">
                                registrar descuento de trabajo
                        </button> <br>
                    Deuda de Trabajo : <?php echo $row_product['DDT'];?><br><br>
                    MATERIALES <br>
                    Costo consumo de inventario : <?php echo $row_product['CI'];?>
                    <button class="badge badge-success" 
                        data-toggle="modal" data-target="#editar_material">
                            Editar  
                    </button> <br>
                    Adelantos consumo de inventario : <?php echo $row_product['AI'];?>
                        <button  class="badge badge-primary"
                            onClick="<?php echo "advance_payments_inv($id_vehicle,'$PERMINV')";?>">
                            registrar adelanto de inventario
                        </button>
                        <button  class="badge badge-secondary"
                            data-toggle="modal" data-target="#historial_pagos_inv" >
                                Historial de pagos de consumo
                        </button> <br>
                    
                    Descuento consumo de inventario : <?php echo $row_product['DI'];?>
                        <button  class="badge badge-danger"
                        onClick="<?php echo "descuento_inv($id_vehicle,'$PERMINV')";?>">
                            registrar descuento de inventario
                        </button> <br>
                    Deuda de Inventario : <?php echo $row_product['DDI'];?><br>
                    
                                    

                </p>
                </div>
            </div>

        
        </div>
    
   
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright © Tamecon 2019</small>
                </div>
            </div>
        </footer>
    <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
        </a>
        <!-- Logout Modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "Cerrar sesión" para confirmar</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" href="../logout.php">Cerrar sesión</a>
            </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="historial_pagos_inv" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Historial de Pagos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                        $sql_record_payments =mysqli_query($conexion,"SELECT *
                        FROM advance_payments_inventory WHERE id_v=$id_vehicle");
                        while($records= mysqli_fetch_array($sql_record_payments)){
                            echo " nro :".$records['id']."<br>";
                            echo " monto :".$records['amount']."<br>";
                            echo " fecha pago :".$records['fecha_payment']."<br>";
                            echo " detalles :".$records['detalle']."<br><br>";
                            
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="historial_pagos_job" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Historial de Pagos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                        $sql_record_payments =mysqli_query($conexion,"SELECT *
                        FROM advance_payments_job WHERE id_v=$id_vehicle");
                        while($records= mysqli_fetch_array($sql_record_payments)){
                            echo " nro :".$records['id']."<br>";
                            echo " monto :".$records['amount']."<br>";
                            echo " fecha pago :".$records['fecha_payment']."<br>";
                            echo " detalles :".$records['detalle']."<br><br>";
                            
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="asignar_inv" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Consumo de Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                        <div class="card-header">
                        <i class="fa fa-table"></i> Tabla Productos</div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Accion</th>
                                
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Producto</th>  
                                    <th>Accion</th>
                                
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                $sql_product =mysqli_query($conexion,"SELECT * FROM product");
                                $PI=0;
                                if($PERMINV =='SI'){
                                    $PI=1;
                                }
                                while($producto= mysqli_fetch_array($sql_product)){
                                
                                    echo "<tr>";
                                        echo "<td>".$producto['code_product']."</td>";
                                        echo "<td>".$producto['name_product']."</td>";
                                        //$id_vehicle,"."'$PERMINV',".$producto['precio_salida'].",".$producto['id'])."'
                                        echo "<td>
                                            <button class='btn btn-xs  btn-primary'
                                             onclick='entrega_productos(".$id_vehicle.",".$PI.",".$producto['precio_salida'].",".$producto['id'].',"'.$producto['name_product'].'"'.")'>
                                            <i class='fa fa-plus-circle'></i>
                                            </button>
                                        </td>";
                                    echo "</tr>";
                                }
                                
                                ?>  
                            
                            </tbody>
                            </table>
                        </div>
                        </div>
                        <div class="card-footer small text-muted">
                        Actualizado
                        <script type="text/javascript">
                            var d = new Date();
                            document.write('Fecha: '+d.getDate()+'/'+
                            (d.getMonth()+1),'/'+d.getFullYear(),' '+
                            d.getHours(),':'+(d.getMinutes()<10?'0'+d.getMinutes():d.getMinutes())+':'+d.getSeconds());
                        </script>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="asignar_job" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Asignar un Trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="assign_job.php" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $id_vehicle;?>">
                            <label for="exampleInputEmail1">Detalle de Trabajo</label>
                            <input type="text" required class="form-control" id="exampleInputEmail1" 
                                placeholder="detalles del trabajo realizado" name ="detail">
                            
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Precio de Trabajo</label>
                            <input type="text" required class="form-control" id="exampleInputPassword1"
                                 placeholder="precio de trabajo realizado" name="price">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            
                        </div>
                       
                    </form>
                </div>
                
                </div>
            </div>
        </div>
        <div class="modal fade" id="salida" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Fecha de Salida</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                       <form name="out" action="salida_vehicle.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id_vehicle;?>"> <br>
                            <input type="date" name="fecha"> <br>
                            <input type="submit">
                       </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                </div>
                </div>
            </div>
        </div>
        <!-- Editar Material -->
        <div class="modal fade bd-example-modal-lg" id="editar_material" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar materiales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            
                            <th>Accion</th>
                        
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>  
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Accion</th>
                        
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $sql_product =mysqli_query($conexion,"SELECT * FROM inventory_consumption where id_v=$id_vehicle");
                        $PI=0;
                        if($PERMINV =='SI'){
                            $PI=1;
                        }
                        while($producto= mysqli_fetch_array($sql_product)){
                        
                            echo "<tr>";
                                echo "<td>".$producto['fecha_entrega']."</td>";
                                echo "<td>".$producto['n_product']."</td>";
                                echo "<td>".$producto['precio_entrega']."</td>";
                                echo "<td>".$producto['cantidad_entrega']."</td>";
                                
                                //$id_vehicle,"."'$PERMINV',".$producto['precio_salida'].",".$producto['id'])."'
                                echo "<td>
                                    <button class='btn btn-xs  btn-primary'
                                     onclick='update_product(".$id_vehicle.",".$producto['id'].",".$producto['precio_entrega'].",".$producto['cantidad_entrega'].',"'.$producto['n_product'].'"'.")'>
                                    <i class='fa fa-pencil'></i>
                                    </button> 
                                    <button class='btn btn-xs  btn-danger'
                                     onclick='remove_product(".$producto['id'].",".$id_vehicle.")'>
                                    <i class='fa fa-trash'></i>
                                    </button>
                                </td>";
                            echo "</tr>";
                        }
                        
                        ?>  
                    
                    </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer">
              
              </div>
            </div>
          </div>
        </div>      

        <div class="modal fade bd-example-modal-lg" id="editar_trabajo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar Trabajos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Detalle</th>
                            <th>Precio</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Detalle</th>
                            <th>Precio</th>
                            <th>Accion</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $sql_product =mysqli_query($conexion,"SELECT * FROM assign_job where id_v=$id_vehicle");
                        $PI=0;
                        if($PERMINV =='SI'){
                            $PI=1;
                        }
                        while($producto= mysqli_fetch_array($sql_product)){
                        
                            echo "<tr>";
                                
                                echo "<td>".$producto['detail']."</td>";
                                echo "<td>".$producto['price_job']."</td>";
                                
                                //$id_vehicle,"."'$PERMINV',".$producto['precio_salida'].",".$producto['id'])."'
                                echo "<td>
                                    <button class='btn btn-xs  btn-primary'
                                    onclick='update_job(".$id_vehicle.",".$producto['id'].",".$producto['price_job'].',"'.$producto['detail'].'"'.")'>
                                    <i class='fa fa-pencil'></i>
                                    </button> 
                                    <button class='btn btn-xs  btn-danger'
                                     onclick='remove_trabajo(".$producto['id'].",".$id_vehicle.")'>
                                    <i class='fa fa-trash'></i>
                                    </button>
                                </td>";
                            echo "</tr>";
                        }
                        
                        ?>  
                    
                    </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer">
              
              </div>
            </div>
          </div>
        </div>     
        <!-- Bootstrap core JavaScript-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../design/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Page level plugin JavaScript-->
        <script src="../vendor/chart.js/Chart.min.js"></script>
        <script src="../design/datatables/jquery.dataTables.js"></script>
        <script src="../design/datatables/dataTables.bootstrap4.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="../js/sb-admin.min.js"></script>
        <!-- Custom scripts for this page-->
        <script src="../js/sb-admin-datatables.min.js"></script>
        <script src="../js/sb-admin-charts.min.js"></script>
  </div>
</body>

</html>