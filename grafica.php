<?php
session_start();


if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: login.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gestor de Conetenido</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
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
<script>
  
   var form;
</script>
</head>

<body>
   
    <!-- Navigation -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
         
          <a class="navbar-brand" href="#" style="color:white;">Gestor de Envios de SMS</a>
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
            <li><a href="index.php">Programaci&oacute;n de Env&iacute;os</a></li>
            <li class="active"><a href="grafica.php">Estad&iacute;sticas de Env&iacute;os</a></li>    
          </ul>
        </div>
      </div>
      <br/>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          
          <div class="row">
           <div class="col-md-3">
                 <div class="form-group">
                         
                    <div class='input-group date' id='fechaIniDiv'>
                         <input name="fechaIni"  id="fechaIni" readonly="readonly" type='text' class="form-control" />
                        <span id="da" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                         </span>
                    </div>
                  </div> 

           </div>
           <div class="col-md-3">
                 
                  <div class="form-group">
                         
                    <div class='input-group date' id='fechaFinDiv'>
                         <input name="fechaFin" readonly="readonly"  id="fechaFin"  type='text' class="form-control" />
                        <span id="da" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                         </span>
                    </div>
                  </div> 
           </div>
          </div>
          <br/>
          <div>
            <canvas id="canvas"></canvas>
          </div>
          <div class="col-md-6">
          </div>
            
          <div class="col-md-6">
                
          </div>

           
       </div>
  </div>

<?php

?>
   
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/datepicker.js"></script>
    <script src="js/Chart.min.js"></script>
   <script>
    
  window.onload = function(){

    
      fecha = $('#fechaIniDiv').datetimepicker({
        pickTime:false,
        defaultDate: new Date(new Date()-432000000),
        maxDate: new Date()
      });

      fecha.on("change", function(e){
        callInfo();
      });

      fecha2= $('#fechaFinDiv').datetimepicker({
        pickTime:false,
        maxDate: new Date(),
        defaultDate: new Date(new Date()),
      });

    fecha2.on("change", function(e){
        callInfo();
      });

        callInfo();
   
  }

  function callInfo(){

    $.post( "cgi/getDataInfo.php", 
      { fechaIni: $("#fechaIni").val(),fechaFin: $("#fechaFin").val()})
      .done(function( data ) { 
         canvas= document.getElementById("canvas")
         var ctx = document.getElementById("canvas").getContext("2d");
         ctx.clearRect(0, 0, canvas.width, canvas.height);
         if(window.myLine)
         window.myLine.removeData()
         window.myLine = new Chart(ctx).Line(data, {
          responsive: true,
           scaleShowLabels: true,
           bezierCurve : false,
           scaleBeginAtZero: true

         });

    },"json");

  }

  </script>

</body>

</html>