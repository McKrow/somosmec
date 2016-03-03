<?php
  session_start();
  if(isset($_SESSION['user']) && $_SESSION['user']!="")
    header("Location: index.php");
  include_once("cgi/config.php"); 
  include("cgi/LocalDB.php"); 
  $msg="";
  $db = new LocalDB;
  $db->connect($HOST, $DATABASE, $USER, $PASSWORD);
  if(isset($_POST['usuario']) && isset($_POST['password'])){
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $idclient= $db->validateUser($usuario, $password);
    if($idclient==null)
      $msg="Usuario o password incorrectos";
    else{
      $_SESSION['user'] = $_POST['usuario'];
      $_SESSION['idclient'] = $idclient;
      $result = $db->getUserCodes($idclient);
      while($row = mysqli_fetch_array($result)) {
        $_SESSION["ussd"]= $row["code"];
        $_SESSION["idcode"]= $row["idcode"];
      }
      header("Location: index.php");
    }

 }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mobile Easy Communication (MEC)</title>

   
    <link href="css/bootstrap.min.css" rel="stylesheet">

   
 <link href="css/bootstrap-theme.min.css" rel="stylesheet">
 <link href="css/datepicker.css" rel="stylesheet">

  <link href="css/estilo.css" rel="stylesheet">
 <link href="dropzone/css/dropzone.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
   
   <div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Mobile Easy Communication (MEC)</h1>
            <div class="account-wall">
                
                <form method="post" action="login.php" class="form-signin">
                <input name="usuario" type="text" class="form-control" placeholder="Usuario" required autofocus>
                <input name="password" type="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                   Ingresar</button>
                
                
                </form>
              <div><?php echo $msg ?></div>
            </div>
            
        </div>
    </div>
</div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
   

</body>

</html>