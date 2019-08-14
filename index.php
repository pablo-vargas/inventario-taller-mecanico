<?php 
   include ("database/conexion.php");
   session_start();
   if(isset($_SESSION['id_user'])){
     header("Location: inventory.php");
   }

   if(isset($_POST["login"])){
        $user = mysqli_real_escape_string($conexion,$_POST['user']);
        $password = mysqli_real_escape_string($conexion,$_POST['password']);
        $encrypted = sha1($password);
        $sql_consulta = "SELECT id FROM usuario WHERE login ='$user' AND password ='$encrypted'" ;
        $result_consulta=$conexion->query($sql_consulta);
        $filas = $result_consulta->num_rows;
        if($result_consulta->num_rows >0){

            $result  = $result_consulta->fetch_assoc();
            $_SESSION['id_user'] = $result['id'];
            header("Location: inventory.php");
        }
        else{
            echo "<script>
                alert('Usuario o Contraseña Incorrectos');
                window.location = 'index.php';
                </script>";
        }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/fonts/font-awesome/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="design/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="design/css/util.css">
	<link rel="stylesheet" type="text/css" href="design/css/main.css">
    
    <title>Taller Mecanico Condor</title>
    
</head>
<body>
    <header>
    
    </header>
    <section>
        <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
            <div class="limiter">
                <div class="container-login100" style="background-image: url('images/img-01.jpg');">
                    <div class="wrap-login100 p-t-190 p-b-30">
                        <form class="login100-form validate-form">
                            <div class="login100-form-avatar">
                                <img src="images/avatar-01.jpg" alt="AVATAR">
                            </div>

                            <span class="login100-form-title p-t-20 p-b-45">
                                Taller Mecanico Condor
                            </span>

                            <div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
                                <input class="input100" type="text" name="user" placeholder="Username">
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>

                            <div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
                                <input class="input100" type="password" name="password" placeholder="Password">
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>

                            <div class="container-login100-form-btn p-t-10">
                                <button class="login100-form-btn" name="login">
                                    Login
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </form>

    </section>
    <footer>
    </footer>
</body>
</html>