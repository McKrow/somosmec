<?php
    session_start();

  if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: login.php");
///
    include("cgi/config.php"); 
    include("cgi/LocalDB.php"); 


     $db = new LocalDB;
     $db->connect($HOST, $DATABASE, $USER, $PASSWORD);

   $userID= $_SESSION['idclient'];
   $idcode="1";
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
            <li class="active"><a href="tree.php">Creaci&oacute;n de Arbol</a></li>   
            <li><a href="graph.php">Gr&aacute;ficos</a></li>
            <li><a href="test.php">Test</a></li>        
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h3 class="page-header">Creaci&oacute;n de Arbol</h3>
          Arrastra tu menu disponible (izquierda)  al cuadro que ser&aacute; tu Menu de Inicio
           <div class="pull-right"> <a id="saveMenu"  data-loading-text="Loading..."  class="btn btn-primary" href="javascript:return false" role="button">
          <span class="glyphicon glyphicon-floppy-save"></span> Salvar </a> </div>
          <br></br>
          <div id="formRow" class="row">

            <div class="col-xs-6">
             <div class="panel panel-info">
               <div class="panel-heading">Menu Disponible</div>
              <div class="panel-body">
              <ul  class="list-group" style="min-height: 50px; list-style-type: none;">
            <?php

            $items = $db->getFinalMenu();
            while($item = mysqli_fetch_array($items)) {
              if($item["final"]=="")
                echo '<li id="'.$item["idmenu"].'" class="list-group-item"> <div><span aria-hidden="true" class="glyphicon glyphicon-option-vertical"></span><span>'.$item["name"]."</span></div></li>";
            }
          ?>
          </ul>
        </div>
      </div>
        </div>
        <div class="col-xs-6">
          <div class="panel panel-primary">
            <div class="panel-heading">Menu Final</div>
              <div class="panel-body">
                  <ul id="finalMenu" class="list-group" style="min-height: 50px;list-style-type: none;">
            <?php

            //$items = $db->getFinalMenu();
            mysqli_data_seek($items, 0); 
            while($item = mysqli_fetch_array($items)) {
              if($item["final"]!="")
               echo '<li id="'.$item["idmenu"].'" class="list-group-item"> <div><span aria-hidden="true" class="glyphicon glyphicon-option-vertical"></span><span>'.$item["name"]."</span></div></li>";
            }
          ?>
          
            
          </ul>
              </div>
            </div>


        </div>
          </div>
       </br>
       <div id="startMenu"></div>
    
      </div>
    </div>

    <!-- /.container -->

<script>
    
    $("#addMenu").click(function(){
      var btn = $("#addMenu").button('loading')
       var success = function() {
          addMenu($("#name").val(),$("#label").val())
          btn.button('reset')
      };  

      var data =  { name: $("#name").val(), label: $("#label").val(), action:"add", table:"menu" } ;
       callAjax(data,success); 
        event.preventDefault()
    })

function callAjax(data, onSucces){

  var posting = $.post( "cgi/api.php", data);
  posting.done(function( data ) {
    if(data.error>0){
      onSucces();
    }else{
      //TODO mostrar error en la conexion
    }
  });


}



$(".list-group").on('click', 'li', function (e) {
    if (e.ctrlKey || e.metaKey) {
        $(this).toggleClass("selected");
    } else {
        $(this).addClass("selected").siblings().removeClass('selected');
    }
}).sortable({
    connectWith: "ul",
    delay: 150, //Needed to prevent accidental drag when trying to select
    revert: 0,
    helper: function (e, item) {
        //Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
        if (!item.hasClass('selected')) {
            item.addClass('selected').siblings().removeClass('selected');
        }
        
        //////////////////////////////////////////////////////////////////////
        //HERE'S HOW TO PASS THE SELECTED ITEMS TO THE `stop()` FUNCTION:
        
        //Clone the selected items into an array
        var elements = item.parent().children('.selected').clone();
        
        //Add a property to `item` called 'multidrag` that contains the 
        //  selected items, then remove the selected items from the source list
        item.data('multidrag', elements).siblings('.selected').remove();
        
        //Now the selected items exist in memory, attached to the `item`,
        //  so we can access them later when we get to the `stop()` callback
        
        //Create the helper
        var helper = $('<li/>');
        return helper.append(elements);
    },
    stop: function (e, ui) {
        //Now we access those items that we stored in `item`s data!
        var elements = ui.item.data('multidrag');
        
        //`elements` now contains the originally selected items from the source list (the dragged items)!!
        
        //Finally I insert the selected items after the `item`, then remove the `item`, since 
        //  item is a duplicate of one of the selected items.
        ui.item.after(elements).remove();
    }

});

$("#saveMenu").click(function(){

   var btn = $(this).button('loading')
    var items = new Array();
    var items = new Array();
    jQuery("#finalMenu li").each(function(){
      if($(this).attr("id")!= "")
          items.push($(this).attr("id"));
    });

    var success = function (){
      btn.button('reset');
    }
     var data =  { idcode:"<?php echo $idcode ?>", action:"add", table:"tree", items:items } ;
       callAjax(data,success); 
      event.preventDefault()

})

</script>


</body>

</html>