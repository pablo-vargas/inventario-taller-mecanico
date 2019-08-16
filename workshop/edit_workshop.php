<?php
  include("../database/conexion.php");
  session_start();
  if(!isset($_SESSION['id_user'])){
    header("Location: index.php");
  }
 

  $id_user = $_SESSION['id_user'];
  $sql = "SELECT name_user,id FROM usuario WHERE id = '$id_user'";
  $resultado = $conexion->query($sql);
  $row = $resultado->fetch_assoc();

  //id GET JOB
  $id_vehicle = $_GET['id'];
  $result_product = $conexion->query("SELECT id,date(fecha_ingreso) AS fec ,placa,id_assistant,tipo_trabajo,carnet_cliente FROM vehicle_entry WHERE id=$id_vehicle");
  $row_product = $result_product->fetch_assoc();
  $id_av=utf8_decode($row_product['id_assistant']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Vehiculo en taller</title>
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
              <a class="nav-link" href="new_product.php">
                  <i class="fa fa-fw fa-usd"></i>
                  <span class="nav-link-text">Vender</span>
                  <span class="badge badge-pill badge-primary">12 New</span>
              </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
              <a class="nav-link" href="tables.html">
                  <i class="fa fa-fw fa-table"></i>
                  <span class="nav-link-text">Tables</span>
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
                <a href="login.html">Ayudantes</a>
                </li>
                <li>
                <a href="register.html">Clientes</a>
                </li>
                <li>
                <a href="forgot-password.html">Vehiculos</a>
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
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="layout_workshop.php">Inicio / Taller </a>
                </li>
                <li class="breadcrumb-item active">Editar Vehiculo en taller</li>
            </ol>
            <?php
            
                if(isset($_POST['register'])){
                    $carnet = mysqli_real_escape_string($conexion,$_POST['carnet']);
                    $placa = mysqli_real_escape_string($conexion,$_POST['placa']); 
                    $assist = mysqli_real_escape_string($conexion,$_POST['assistant']);
                    $fecha = mysqli_real_escape_string($conexion,$_POST['fecha']);
                    $tipo = mysqli_real_escape_string($conexion,$_POST['costo']);

                    
                    $add_product = "CALL edit_workshop($id_vehicle,'$placa',$carnet,$assist,'$fecha',$tipo)";
                    $ejecutar = $conexion->query($add_product);

                    echo "<script> window.location = 'edit_workshop.php?id=$id_vehicle'</script>";
                }
            ?>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="addproduct" 
                action="<?php $_SERVER['PHP_SELF']; ?>" role="form">

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Carnet *</label>
                    <div class="col-md-6">
                        <input type="text" name="carnet"  required id="product_code" class="form-control" 
                        id="barcode" placeholder="Numero de Carnet de Cliente" value="<?php echo $row_product['carnet_cliente'];?>">
                    </div>
                </div> 
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Placa *</label>
                    <div class="col-md-6">
                        <input type="text" name="placa"  required id="product_code" class="form-control" 
                        id="barcode" placeholder="Placa de Vehiculo" value="<?php echo $row_product['placa'];?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Asistente a Cargo *</label>
                    <div class="col-md-6">
                        <select name="assistant" class="form-control" >
                            <option value ="">Selecciona un Asistente</option>
                            <?php
                                
                                $sql_assistant =mysqli_query($conexion,"SELECT * FROM assistant");
                                
                                
                                while($assistant= mysqli_fetch_array($sql_assistant)){
                                    if($assistant['id'] ==$row_product['id_assistant'] ){
                                        echo "<option value='$assistant[id]' selected>". $assistant['nombre']."</option>";
                                    }else{
                                        echo "<option value='$assistant[id]' >". $assistant['nombre']."</option>";    
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha Ingreso  *</label>
                    <div class="col-md-6">
                        <input type="date" name="fecha" required class="form-control" 
                            value="<?php echo $row_product['fec'];?>"
                        >
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label  class="col-lg-2 control-label">Tipo de trabajo</label>
                    <div class="col-md-6">
                        <select name="costo" class="form-control" >
                            <option value ="">Selecciona el tipo de trabajo</option>
                            <option value ="1" <?php if($row_product['tipo_trabajo']== '1') echo "selected";?>>publico</option>
                            <option value ="2" <?php if($row_product['tipo_trabajo']== '2') echo "selected";?>>intermedio</option>
                            <option value ="3" <?php if($row_product['tipo_trabajo']== '3') echo "selected";?>>con factura</option> 
                        </select>
                    </div>
                </div>
                
               

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" name="register" class="btn btn-primary">Modificar Vehiculo en taller</button>
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