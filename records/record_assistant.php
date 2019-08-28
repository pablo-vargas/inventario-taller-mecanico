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
                <a href="../vehiculo/layout_vehiculo.php">Vehiculos</a>
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
                <a href="#">Usuarios</a>
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
                <a href="../inventory.php">Inicio </a>
            </li>
            <li class="breadcrumb-item active">Historial de mecanico</li>
            </ol>
            <form class="form-horizontal" method="POST"
                action="<?php $_SERVER['PHP_SELF']; ?>" role="form">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">id o carnet de mecanico</label>
                        <input type="text" name="assistant" class="form-control" id="validationCustom01"   required>
                        
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Fecha Inicial</label>
                        <input type="date" name="date1" class="form-control" required>
                    
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustomUsername">Fecha Final</label>
                        <div class="input-group">
                            
                            <input type="date" name="date2" class="form-control" aria-describedby="inputGroupPrepend" required>
                            
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-primary" name="buscar" type="submit">Ver Historial</button>             
            </form>

            <div class="card mb-3">
                <div class="card-header">
                <i class="fa fa-table"></i> Historial de Mecanico</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                        <th>Nro</th>
                        <th>Placa</th>
                        <th>Cliente</th>
                        <th>Asistente</th>
                        <th>fecha ingreso</th>
                        <th>Accion</th>
                        
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <th>Nro</th>
                        <th>Placa</th>
                        <th>Cliente</th>
                        <th>Asistente</th>
                        <th>Fecha ingreso</th>
                        <th>Accion</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if(isset($_POST['buscar'])){

                            $id_a = mysqli_real_escape_string($conexion,$_POST['assistant']);
                            $fechai = mysqli_real_escape_string($conexion,$_POST['date1']); 
                            $fechaf = mysqli_real_escape_string($conexion,$_POST['date2']);

                            $sql_vehicle =mysqli_query($conexion,"SELECT
                            V.id AS Nro,V.placa,
                            V.carnet_cliente AS cliente,
                            date(V.fecha_ingreso) AS fi,
                            A.nombre as assist
                            FROM vehicle_entry V INNER JOIN assistant A on V.id_assistant= A.id
                            where (id_assistant=".$id_a." OR A.carnet=$id_a) AND (date(fecha_ingreso) BETWEEN '".$fechai."' AND '".$fechaf."')");
                            while($vehicle= mysqli_fetch_array($sql_vehicle)){
                            
                                echo "<tr>";
                                echo "<td>".$vehicle['Nro']."</td>";
                                echo "<td>".$vehicle['placa']."</td>";
                                echo "<td>".$vehicle['cliente']."</td>";
                                echo "<td>".$vehicle['assist']."</td>";
                                echo "<td>".$vehicle['fi']."</td>";
                                echo "<td>
                                            <a class='btn btn-xs  btn-info' target='blank' href='../workshop/record_workshop.php?id=$vehicle[Nro]'>
                                                <i class='fa fa-fw fa-info-circle'></i>
                                            </a>
                                        </td>";
                                echo "</tr>";
                            }
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