<?php
    session_start();

  if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: login.php");
///
    include("cgi/config.php"); 
    include("cgi/LocalDB.php"); 


     $db = new LocalDB;
     $db->connect($HOST, $DATABASE, $USER, $PASSWORD);

   $idclient= $_SESSION['idclient'];
   $idcode=$_SESSION["idcode"];
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

    <!-- Bootstrap Core CSS -->
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
 <link href="js/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/estilo.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/drag.js"></script>

</head>

<body>
   
    <!-- Navigation -->
<div class="navbar navbar-inverse navbar-fixed-top"  role="navigation">
      <div class="container-fluid">
        <div class="navbar-header" >
          
          <a class="navbar-brand" style="color:white" href="#">Mobile Easy Communication (MEC)</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            
            <li><a href="logout.php">Cerrar Sesi&oacute;n</a></li>
          </ul>
          
        </div>
      </div>
    </div>

   <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li ><a href="index.php">Creaci&oacute;n de Menu</a></li>
            <li ><a href="tree.php">Creaci&oacute;n de Arbol</a></li>   
            <li><a href="graph.php">Gr&aacute;ficos</a></li>
           
            <li class="active"><a href="test.php">Test</a></li>        
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h3 class="page-header">Prueba de Servicio</h3>
      
        <div id="formRow" class="row">  
        <div class="col-xs-6">
          <div class="panel panel-primary">
            <div class="panel-heading">Menu USSD</div>
              <div class="panel-body">
                 

               <form>
                  <div class="form-group">
                    <label for="number">Numero de Celular</label>
                    <input id="mobile" type="text" class="form-control" id="number" placeholder="Celular">
                  </div>
                  <div class="form-group">
                    <label for="command">Comando</label>
                    <input id="command" type="text" class="form-control" id="command" placeholder="Comando USSD">
                  </div>
                  
                 <a id="btnSend"  data-loading-text="Loading..."  class="btn btn-primary" href="javascript:return false" 
                  role="button">
                 </span> Enviar
                 </a>
                 <a id="btnCancel"   class="btn btn-default" href="./test.php" role="button">
                   Cancelar
                 </a>
              </form>
              </div>
            </div>


        </div>
          </div>
     
       <div id="startMenu"></div>
       <div id="formRow" class="row">  
        <div   class="col-xs-6">

          <div class="panel panel-primary">
              <div id="response" class="panel-body">
            </div>
          </div>

        </div>
      </div>
      </div>
    </div>

    <!-- /.container -->

<script>
    

var transaction ="BEGIN"

function callAjax(data, onSucces){

  var posting = $.post( "cgi/api.php", data);
  posting.done(function( data ) {
   // if(data.error>0){
    //  onSucces(data);
      $("#response").append("<p>"+data.label+"</p>");
      for( i = 0; i<data.items.length; i++){
        if( data.items[i].name !="")
          $("#response").append("<p>"+ data.items[i].name + "</p>");

      }

      onSucces();
    //}else{
      //TODO mostrar error en la conexion
    //}
  });


}




$("#btnSend").click(function(){

   var btn = $(this).button('loading')
   var items = new Array();
   var items = new Array();
    $("#response").html("");
    usermobile = $("#mobile").val()
    usercommand = $("#command").val()

    var success = function (){
      btn.button('reset');
      transaction ="CONTINUE"
      //$("#response").html(val);
    }
     var data =  { idcode:"<?php echo $idcode ?>", idclient:"<?php echo $idclient ?>",table:transaction, action:"test", command:usercommand, mobile:usermobile } ;
       callAjax(data,success); 
      event.preventDefault()

})

/*$("#btnCancel").click(function(){

   var btn = $(this).button('loading')
    var transaction ="BEGIN"
    var success = function (){
     btn.button('reset');
      //$("#response").html(val);
    }
    var data =  { idcode:"<?php echo $idcode ?>", idclient:"<?php echo $idclient ?>",table:transaction, action:"test", command:usercommand, mobile:usermobile } ;
       callAjax(data,success); 
      event.preventDefault()   
    

})*/



</script>


</body>

</html>