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
 
  $sql_assistant =mysqli_query($conexion,"SELECT * FROM assistant");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingreso Taller</title>
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
                    <a href="layout_workshop.php">Inicio / Taller </a>
                </li>
                <li class="breadcrumb-item active">Nuevo Ingreso Vehiculo</li>
            </ol>
            <?php
                if(isset($_POST['register'])){
                    $carnet = mysqli_real_escape_string($conexion,$_POST['carnet']);
                    $placa = mysqli_real_escape_string($conexion,$_POST['placa']); 
                    $assist = mysqli_real_escape_string($conexion,$_POST['assistant']);
                    $fecha = mysqli_real_escape_string($conexion,$_POST['fecha']);
                    $tipo = mysqli_real_escape_string($conexion,$_POST['costo']);

                    
                   
                    $add_product = "CALL add_workshop('$placa',$carnet,$assist,'$fecha',$tipo)";
                    $ejecutar = $conexion->query($add_product);
                    
                    echo "  <div class='alert alert-success' role='alert'>
                                Registro Exitoso!
                            </div>";
                    echo "<script>window.location = 'layout_workshop.php'</script>";
                    
                }
            ?>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="addproduct" 
                action="<?php $_SERVER['PHP_SELF']; ?>" role="form">
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Carnet *</label>
                    <div class="col-md-6">
                        <input type="text" name="carnet"  required id="product_code" class="form-control" 
                        id="barcode" placeholder="Numero de Carnet de Cliente">
                    </div>
                </div> 
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Placa *</label>
                    <div class="col-md-6">
                        <input type="text" name="placa"  required id="product_code" class="form-control" 
                        id="barcode" placeholder="Placa de Vehiculo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Asistente a Cargo *</label>
                    <div class="col-md-6">
                        <select name="assistant" class="form-control" >
                            <option value ="">Selecciona un Asistente</option>
                            <?php
                                while($assistant= mysqli_fetch_array($sql_assistant)){
                                    echo "<option value='$assistant[id]'>". $assistant['nombre']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha Ingreso  *</label>
                    <div class="col-md-6">
                        <input type="date" name="fecha" required class="form-control" 
                          
                        >
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label  class="col-lg-2 control-label">Tipo de trabajo</label>
                    <div class="col-md-6">
                        <select name="costo" class="form-control" >
                            <option value ="">Selecciona el tipo de trabajo</option>
                            <option value ="1">publico</option>
                            <option value ="2">intermedio</option>
                            <option value ="3">con factura</option> 
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" name="register" class="btn btn-primary">Agregar Vehiculo</button>
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