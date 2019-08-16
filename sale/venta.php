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
  $sql_product =mysqli_query($conexion,"SELECT * FROM product");
  
  $sql_consulta ="SELECT COUNT(*) total FROM jobs";
  $sql_jobs = $conexion->query($sql_consulta);
  $jobs = $sql_jobs->fetch_assoc();

  $sql_vehicle ="SELECT COUNT(*) total FROM vehicle";
  $result_vehicle = $conexion->query($sql_vehicle);
  $vechicle = $result_vehicle->fetch_assoc();

  $sql_entry ="SELECT COUNT(*) total FROM vehicle_entry";
  $result_vehicle_entry = $conexion->query($sql_entry);
  $vechicle_entry = $result_vehicle_entry->fetch_assoc();

  $sql_entry_deuda ="SELECT COUNT(*) total FROM vehicle_entry WHERE costo_trabajo>(descuento_trabajo+adelantos) OR 
    costo_inventario>(descuento_inventario+adelantos_inventario)";
  $result_vehicle_entry_deuda = $conexion->query($sql_entry_deuda);
  $vechicle_entry_deuda = $result_vehicle_entry_deuda->fetch_assoc();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Ventas</title>
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
        <a class="navbar-brand" href="inventory.php">Tamecon</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
              <a class="nav-link" href="inventory.php">
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
                <a href="layout_assistant.php">Ayudantes</a>
                </li>
                <li>
                <a href="layout_clients.php">Clientes</a>
                </li>
                <li>
                <a href="vehiculo/layout_vehiculo.php">Vehiculos</a>
                </li>
                <li>
                <a href="blank.html">Proveedores</a>
                </li>
            </ul>
            </li>
            
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
            <a class="nav-link" href="#">
                <i class="fa fa-fw fa-info-circle"></i>
                <span class="nav-link-text">Deudores Morosos</span>
            </a>
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
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="../inventory.php">Inicio</a>
          </li>
          <li class="breadcrumb-item active">Ventas</li>
        </ol>
   <!-- Icon Cards-->
        
    
    <div class="col-xl-3 col-sm-6 mb-3 float-rigth">
      <a data-toggle="tooltip" data-placement="right" title="Nuevo Producto"
        href="new_product.php" class="btn btn-outline-primary ">Nuevo Producto
      </a>
    </div>
      <!-- Example DataTables Card-->
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
                  <th>Precio Entrada</th>
                  <th>Precio Salida</th>
                  <th>Unidad</th>
                  <th>En Inventario</th>
                  <th>Accion</th>
                  
                </tr>
              </thead>
              <tfoot>
                <tr>
                    <th>Codigo</th>
                    <th>Producto</th>
                    <th>Precio Entrada</th>
                    <th>Precio Salida</th>
                    <th>Unidad</th>
                    <th>En Inventario</th>
                    <th>Accion</th>
                  
                </tr>
              </tfoot>
              <tbody>
                <?php
                  while($producto= mysqli_fetch_array($sql_product)){
                  
                    echo "<tr>";
                      echo "<td>".$producto['code_product']."</td>";
                      echo "<td>".$producto['name_product']."</td>";
                      echo "<td>".$producto['precio_entrada']."</td>";
                      echo "<td>".$producto['precio_salida']."</td>";
                      echo "<td>".$producto['unidad']."</td>";
                      echo "<td>".$producto['cantidad']."</td>";
                      echo "<td>
                        <input type='submit' value='' class='btn btn-xs  btn-warning' >
                          <i class='fa fa-fw fa-shopping-cart'></i>
                        </input>
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
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
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