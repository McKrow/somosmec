<?php
    session_start();

  if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: login.php");

    include("cgi/config.php"); 
    include("cgi/LocalDB.php"); 

    
     $db = new LocalDB;
     $db->connect($HOST, $DATABASE, $USER, $PASSWORD);

   
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

    <script>
   
  
 
/*
  function  setEvents(id){
        $("#download"+id).click(function() {
            window.location = 'cgi/download.php?id='+id;
        });

        $("#trash"+id).click(function() {
            var r = confirm("Esta seguro de borrar este registro");
                if (r == true) {
                    var posting = $.post( "cgi/delete.php", { "accion":"DEL","id":id} );

                    posting.done(function( data ) {
                        $('#tr'+id).remove();
                    });
                }
        });

  }
*/


/** 
Metodo para agregar los eventos pricipales de cada lista
@param idMenu, Identificador del Menu
*/

function setEvents(idMenu){
  
  $( "#list_" +idMenu).sortable();
  $( "#list_"+idMenu).disableSelection();
     

  $("#save_"+idMenu).click(function(){
   var btn = $(this).button('loading')
    var items = new Array();
    jQuery("#list_"+idMenu+" li span").each(function(){
      if($(this).text()!= "")
          items.push($(this).text());
    });

    var success = function (data){
      btn.button('reset');
    }
     var data =  { idmenu:idMenu, action:"add", table:"item", items:items } ;
       callAjax(data,success); 
      event.preventDefault()

  });


  $("#close_"+idMenu).click(function (){
     var r = confirm("¿Esta seguro de borrar este menu?");
                if (r == true) {
                  var success = function ( data){
                     $("#op_"+idMenu).remove()
                  }
                  var data =  { idmenu:idMenu, action:"delete", table:"menu" } ;
                  callAjax(data,success); 

                }
        
  })
  $("#add_"+idMenu).click(function(){

    id = getId("a");
    idValue = idMenu+"_"+ id

   var htmlValue = '<div><span aria-hidden="true" class="glyphicon glyphicon-option-vertical"></span><span>'+  $("#txtItem_"+idMenu).val()
       htmlValue += '</span><div class="pull-right" style="float:right;">'
       htmlValue += '<button id="trash_'+idValue+'" type="button" class="pull-right btn btn-default btn-xs">'
       htmlValue +='<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'
       htmlValue +='</button>'
       htmlValue +='</div>'
       htmlValue +='</div></li>';

   $("#txtItem_"+idMenu).val("")
    var el = document.createElement('li');

   $( el ,{
    'class': 'list-group-item ui-sortable-handle'
  }).appendTo("#list_"+idMenu);
   $(el).attr({id:"item_"+idValue});
   $(el).html(htmlValue)
   $(el).addClass("list-group-item ui-sortable-handle")
   
   setTrashItemEvent(idMenu, id)
    event.preventDefault()

})

}


function getId(preffix){
  return preffix + Math.floor((Math.random() * 100000) + 1);
}

function addMenu(name, level, id){
 //id = getId("a");
 var el = document.createElement('div');
 $( el ,{
    'class': 'row'
  }).insertAfter($("#startMenu"));
$(el).css( "display:none; padding-left: 0px;" );

 //$(el).addClass("row")
var htmlValue='<div id="op_'+id+'" style="padding-left: 0px;"" class="col-xs-6">'
  htmlValue+= '<div class="panel panel-info">'
  htmlValue+= ' <div class="panel-heading">'
  htmlValue+= '<button type="button" id="close_'+id+'" class="close" aria-label="Close"><span aria-hidden="true"></span>&times;</button>'
  htmlValue+= '<h5><b>'+name +"</b> "+level+'</h5>'
  htmlValue+= ' </div> '
  htmlValue+= ' <div class="panel-body">'
  htmlValue+= '      <ul id="list_'+id+'" class="list-group"></ul>'
  htmlValue+= '    </div>'
  htmlValue+= '    <div class="panel-footer form-inline">  '
  htmlValue+= '      <input id="txtItem_'+id+'" type="text" class="form-control"  placeholder="Nuevo item">'
   htmlValue+= '      <a id="add_'+id+'" class="btn btn-default btn-sm" href="#"  role="button">Agregar</a>'
   htmlValue+= '      <a id="save_'+id+'" class="btn btn-primary btn-sm" href="#" data-loading-text="Espere..."  role="button">Guardar</a>'
   htmlValue+= '   </div>'                
   htmlValue+= ' </div>'
  htmlValue+= '</div>'
$(el).html(htmlValue)
$(el).hide().fadeIn(500)
  event.preventDefault()
   setEvents(id)
}

function setTrashItemEvent(idMenu,idItem){
  $("#trash_"+idMenu+"_"+idItem).click(function(){
      $("#item_"+idMenu+"_"+idItem).remove()
      event.preventDefault()

  })


}

</script>
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
            <li class="active"><a href="index.php">Creaci&oacute;n de Menu</a></li>
            <li><a href="tree.php">Creaci&oacute;n de Arbol</a></li>   
            <li><a href="graph.php">Gr&aacute;ficos</a></li>
            
            <li><a href="test.php">Test</a></li>        
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h3 class="page-header">Creaci&oacute;n de Menu</h3>

          <div id="formRow" class="row">
           <div class="col-md-12">

<form class="form-inline">
  <div class="form-group">
    <label for="name">Nombre del Menu: </label>
    <input type="text" class="form-control" id="name" placeholder="Nombre del Menu">
  </div>
  <div class="form-group">
    <label for="label">Pregunta a mostrar: </label>
    <input type="text" class="form-control" id="label" placeholder="Etiqueta a mostrar">
  </div>
        <a id="addMenu"  data-loading-text="Loading..."  class="btn btn-primary" href="javascript:return false" role="button">
          <span class="glyphicon glyphicon-plus"></span> Agregar
        </a>
            
</form>


           </div>
          
           
          </div>
       </br>
       <div id="startMenu"></div>
               <?php
                        $result = $db->getMenus();
                        //$cant = = mysqli_fetch_array($result, MYSQL_NUM );

                        $i = 0;
                        while($row = mysqli_fetch_array($result)) {
                       
                       if($i==0){
                        
                        echo "<div class='row'>";
                      }

                        if (($i%2)==0  && $i!=0){
                         echo "</div>";
                         echo "<div class='row'>";
                        }

                        ?>


                        <div id="op_<?php echo $row["idmenu"] ?>" class="col-xs-6">
                       <div class="panel panel-info">
                          <div class="panel-heading">
                              <button type="button" id="close_<?php echo $row["idmenu"] ?>" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h5><?php echo "<b>".$row["name"]."</b>" . ": ".$row["label"] ; ?></h5> 
                          </div>
                          <div class="panel-body">

                              <ul id="list_<?php echo $row["idmenu"]?>" class="list-group">
                           <?php
                               $items = $db->getItemsByMenuId($row["idmenu"]);
                               while($item = mysqli_fetch_array($items)) {
                                 echo '<li id="item_'.$row["idmenu"].'_'.$item["iditem"].'"" class="list-group-item"> <div><span aria-hidden="true" class="glyphicon glyphicon-option-vertical"></span><span>'.$item["name"]."</span>";
                                 ?>
                                   <div class="pull-right" style="float:right;">
                                  

                                    <button id="trash_<?php echo $row["idmenu"]."_".$item["iditem"]?>" type="button" class="pull-right btn btn-default btn-xs">
                                      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                    <script type="text/javascript">
                                    setTrashItemEvent("<?php echo $row["idmenu"] ?>","<?php echo $item["iditem"] ?>")

                                    </script>
   
                                  </div>
                                 <?php

                                  echo '</div></li>';
                               }
                               //glyphicon glyphicon-edit
                               // glyphicon glyphicon-trash
                          ?>
                          </ul>

                          </div>
                            <div class="panel-footer form-inline">  
                              <input id="<?php echo "txtItem_".$row["idmenu"] ?>" type="text" class="form-control"  placeholder="Nuevo item">
                              <a id="<?php echo "add_".$row["idmenu"] ?>"  class="btn btn-default btn-sm" href="javascript:return false" role="button">Agregar</a>
                              <a id="<?php echo "save_".$row["idmenu"] ?>" data-loading-text="Espere..." class="btn btn-primary btn-sm" href="#" role="button">Guardar</a>
                            
                            </div>
                            <script type="text/javascript">
                              setEvents("<?php echo $row["idmenu"] ?>");

                            </script>
                       </div>
                     </div>

              <?php  if (($i%3) == 0  && $i!=0){
                      //   echo "</div>";
                      }

                         $i++;
                } 
                    echo "</div>";

                ?>
            

          
           
 
         
      </div>
    </div>

    <!-- /.container -->

<script>
    
    $("#addMenu").click(function(){
      var btn = $("#addMenu").button('loading')

       var success = function( data) {
          addMenu($("#name").val(),$("#label").val(), data.error);
          btn.button('reset');
           $("#name").val("");
          $("#label").val("");
      };  

      var data =  { name: $("#name").val(), label: $("#label").val(), action:"add", table:"menu" } ;
       callAjax(data,success); 
        event.preventDefault()
    })

function callAjax(data, onSucces){

  var posting = $.post( "cgi/api.php", data);
  posting.done(function( data ) {
    if(data.error>0){
      onSucces(data);
    }else{
      //TODO mostrar error en la conexion
    }
  });


}

</script>


</body>

</html>