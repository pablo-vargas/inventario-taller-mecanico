<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: ../index.php");
  }
  else{
    $id_perm = $_SESSION['id_user'];
    $permission = $conexion->query("SELECT permission_taller FROM usuario where id='$id_perm'");
    $result_perm = $permission->fetch_assoc();
    $PERM = $result_perm['permission_taller'];
    if($PERM != 'SI'){
      echo "<script>
              alert('ACCESO DENEGADO');
              window.location = 'layout_jobs.php';
          </script>";
    }
  }
  $id_user = $_SESSION['id_user'];
  $sql = "SELECT name_user,id FROM usuario WHERE id = '$id_user'";
  $resultado = $conexion->query($sql);
  $row = $resultado->fetch_assoc();
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nuevo Trabajo</title>
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
                    <a href="layout_jobs.php">Inicio / Trabajos </a>
                </li>
                <li class="breadcrumb-item active">Nuevo Trabajo</li>
            </ol>
            <?php
                if(isset($_POST['register'])){
                    $job = mysqli_real_escape_string($conexion,$_POST['job']);
                    $public = mysqli_real_escape_string($conexion,$_POST['public']); 
                    $middle = mysqli_real_escape_string($conexion,$_POST['middle']);
                    $bill = mysqli_real_escape_string($conexion,$_POST['bill']);
                    
                    $verificar = "SELECT * FROM jobs WHERE trabajo='$job'";
                    $resultado_consulta= $conexion->query($verificar);
                    $rows1 = $resultado_consulta->num_rows; 
                    if($rows1 > 0){
                        echo "  <div class='alert alert-danger' role='alert'>
                                    Ya existe un trabajo con esa Descripcion!
                                </div>";
                    }else{
                        $add_product = "CALL add_job('$job',$public,$middle,$bill)";
                        $ejecutar = $conexion->query($add_product);
                        
                        
                        $resultado_consulta= $conexion->query($verificar);
                        $rows = $resultado_consulta->num_rows; 
                        if($rows > 0){

                            echo "  <div class='alert alert-success' role='alert'>
                                        Registro Exitoso!
                                    </div>";
                        }else{
                            echo "  <div class='alert alert-danger' role='alert'>
                                        Registro Fallido!
                                    </div>";
                            
                        }
                    }
                    

                    
                }
            ?>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="addproduct" 
                action="<?php $_SERVER['PHP_SELF']; ?>" role="form">

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Trabajo *</label>
                    <div class="col-md-6">
                        <input type="text" name="job"  required id="product_code" class="form-control" 
                        id="barcode" placeholder="Nombre/Descripcion de Trabajo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">precio publico *</label>
                    <div class="col-md-6">
                        <input type="text" name="public" required class="form-control" id="name" 
                        placeholder="Costo de servicio publico">
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Intermedio</label>
                    <div class="col-md-6">
                        <input type="text" name="middle" class="form-control" id="inputEmail1" 
                        placeholder="Costo de Servicio Intermedio">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Factura</label>
                    <div class="col-md-6">
                        <input type="text" name="bill" class="form-control" id="inputEmail1" 
                        placeholder="Costo de servicio con Factura">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" name="register" class="btn btn-primary">Agregar Trabajo</button>
                    </div>
                </div>
            </form>
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