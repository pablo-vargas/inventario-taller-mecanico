<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }

  $id_user = $_SESSION['id_user'];
  $sql = "SELECT name_user,id FROM usuario WHERE id = '$id_user'";
  $resultado = $conexion->query($sql);
  $row = $resultado->fetch_assoc();


  //id GET Entrada
  $id_entrada = $_GET['id'];

 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adelantos - Pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap core CSS-->
    <link href="../design/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="../design/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="../design/css/sb-admin.css" rel="stylesheet">
    <link href="../design/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <script>
        function entrega_productos(entrada,id){
            var ide,prec,idp,cant;
            
            cant = parseFloat(prompt('Cantidad de entrada',1));
            location.href ="insert_product.php?id="+entrada+"&id_p="+id+"&cant="+cant;
        }
        function edit_entry(id_ent,id,precio,cantidad){
            var price = parseFloat(prompt("Verificar Precio",precio));
            var cant = parseFloat(prompt("Verificar Cantidad",cantidad));
            location.href ="edit_entry.php?id_ent="+id_ent+"&id="+id+"&cant="+cant+"&price="+price; 
        }
        function remove_entry(id, id_e){
            location.href ="remove_entry.php?id="+id+"&idp="+id_e; 
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
                <a href="layout_vehiculo.php">Vehiculos</a>
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
                    <a href="index.php">Inicio / Añadir Productos </a>
                </li>
                <li class="breadcrumb-item active">Añadir Productos</li>
            </ol>
            <div class="col-xl-3 col-sm-6 mb-3 float-rigth">
                <a data-toggle="modal" href="" data-target="#add_pago" class="btn btn-outline-primary ">
                    Añadir Pago
                </a>
            </div>

            <?php
                if(isset($_POST['register'])){
                    $monto = mysqli_real_escape_string($conexion,$_POST['monto']);
                    $detalle = mysqli_real_escape_string($conexion,$_POST['detalle']); 
                    $fecha = mysqli_real_escape_string($conexion,$_POST['fecha']);
                    
                    
                    $verificar = "SELECT * FROM pagos";
                    $resultado_consulta= $conexion->query($verificar);
                    $rows1 = $resultado_consulta->num_rows; 
                    
                    $add_product = "CALL add_pago($id_entrada,$monto,'$detalle','$fecha')";
                    $ejecutar = $conexion->query($add_product);
                    
                    
                    $resultado_consulta= $conexion->query($verificar);
                    $rows = $resultado_consulta->num_rows; 
                    if($rows > $rows1){

                        echo "  <div class='alert alert-success' role='alert'>
                                    Registro Exitoso!
                                </div>";
                    }else{
                        echo "  <div class='alert alert-danger' role='alert'>
                                    Registro Fallido!
                                </div>";
                        
                        
                    }
                
                }
            ?>
            <div class="card mb-3">
                <div class="card-header">
                <i class="fa fa-table"></i> Entrada de Productos</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Precio Entrada</th>
                        
                        
                        <th>Cantidad</th>
                        <th>Accion</th>
                        
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Precio Entrada</th>
                            
                            
                            <th>Cantidad</th>
                            <th>Accion</th>
                        
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                          $sql_pr =mysqli_query($conexion,"SELECT P.code_product as Code,
                                                P.name_product AS Nombre,E.precio as Prec,E.cantidad as Cant,
                                                E.id as ident
                            FROM entrada_prod E inner join product P on E.id_producto= P.id where E.id_entrada=$id_entrada");
                        while($producto= mysqli_fetch_array($sql_pr)){
                        
                            echo "<tr>";
                            echo "<td>".$producto['Code']."</td>";
                            echo "<td>".$producto['Nombre']."</td>";
                            echo "<td>".$producto['Prec']."</td>";
                            echo "<td>".$producto['Cant']."</td>";
                            echo "<td>
                                <button class='btn btn-xs  btn-warning' onclick='edit_entry(".$id_entrada.",".$producto['ident'].",".$producto['Prec'].",".$producto['Cant'].")' >
                                    <i class='fa fa-fw fa-pencil'></i>
                                </button>
                                <button class='btn btn-xs  btn-danger' onclick='remove_entry(".$id_entrada.",".$producto['ident'].")' >
                                    <i class='fa fa-fw fa-trash-o'></i>
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
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                <small>Copyright © Tamecon 2019</small>
                </div>
            </div>
        </footer>
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

        <div class="modal fade" id="add_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Registrar Adelanto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="addproduct" 
                        action="<?php $_SERVER['PHP_SELF']; ?>" role="form">

                        
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">Monto </label>
                            <div class="col-md-12">
                                <input type="text" name="monto" required class="form-control" 
                                placeholder="Monto pagado">
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">Fecha</label>
                            <div class="col-md-12">
                                <input type="date" required name="fecha" class="form-control" id="inputEmail1" 
                                placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">Detalle</label>
                            <div class="col-md-12">
                                <input type="text" name="detalle" class="form-control" id="inputEmail1" 
                                placeholder="Detalles de Pagos">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" name="register" class="btn btn-primary">Registrar Pago</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit_entry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Registro de entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="addproduct" 
                    action="<?php $_SERVER['PHP_SELF']; ?>" role="form">

                    
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 control-label">Monto </label>
                        <div class="col-md-6">
                            <input type="text" name="monto" required class="form-control" 
                            placeholder="Monto pagado">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 control-label">Fecha</label>
                        <div class="col-md-6">
                            <input type="date" required name="fecha" class="form-control" id="inputEmail1" 
                            placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 control-label">Detalle</label>
                        <div class="col-md-6">
                            <input type="text" name="detalle" class="form-control" id="inputEmail1" 
                            placeholder="Detalles de Pagos">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" name="register" class="btn btn-primary">Registrar Pago</button>
                        </div>
                    </div>
                </form>
                
            
            </div>
        </div>
    </div>
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