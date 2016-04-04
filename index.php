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
   
  var itemOption = "";
  var globalItemId = "";
 $( document ).ready(function() {
    

    $("#msg").hide();
    $("#urlInput").hide();

     $("#listmenu").hide()


    $("#itemType").change(function(){
      if($(this).val() ==1){
        $("#menuInput").show();
        $("#urlInput").hide();
      }else{
        $("#menuInput").hide();
        $("#urlInput").show();
      }

    });

    $("#anyText").click(function(){
        if($(this).is(':checked')){
         $("#listmenu").show()
          $("#menuList1 option").remove();
          $("#menuList option").clone().appendTo("#menuList1");
           $("#menuList1 option[value='-1']").remove();

        }else{
           $("#listmenu").hide()
        }
    })



 });

  function showItemModal(){
    $('#modalItem').modal('toggle')

  }


/** 
Metodo para agregar los eventos pricipales de cada lista
@param idMenu, Identificador del Menu
*/

function setEvents(idMenu){
  

 // $( "#list_" +idMenu).sortable();
 // $( "#list_"+idMenu).disableSelection();
     

 


  $("#close_"+idMenu).click(function (){
     var r = confirm("¿Esta seguro de borrar este menu?");
                if (r == true) {
                  var success = function ( data){
                      $("#menuList option[value='"+idMenu+"']").remove();

                     $("#op_"+idMenu).remove()
                  }
                  var data =  { idmenu:idMenu, action:"delete", table:"menu" } ;
                  callAjax(data,success); 

                }
        
  })
  $("#add_"+idMenu).on("click",{"idMenu":idMenu},function(event){
    itemOption = "add";
    idMenu = event.data.idMenu;
    $("#msg").hide();
    showItemModal();

   setEventsAddItem(idMenu,"")
 
})

}

 function setEventsAddItem(idMenu, idItem){

 $( "#addElement" ).unbind( "click" );

    $("#addElement").bind("click",{"idMenu":idMenu},function(event){

    //  itemOption ="add"
      if( $("#itemText").val() ==""){
      $("#msg").html("Por favor escriba la <strong>opcion</strong> que debe mostrarse");
        $("#msg").show();
        return; 
      }


    /*  if($("#itemType").val()==1){
        if($("#menuList").val()==-1){
          $("#msg").html("Por favor seleccione el <strong>menu</strong> que debe mostrarse");
          $("#msg").show();
           return;
        }
      }else{
          if($("#itemUrl").val()==""){
          $("#msg").html("Por favor ingrese la <strong>URL</strong> a invocar");
          $("#msg").show();
          return;
        }
      }*/
    
      addItem(idMenu, idItem);
    
    })

 }

 function addItem(idMenu, idItem){
   //var btn = $(this).button('loading')
    var items = new Array();

    items.push( $("#itemType").val());
    items.push( $("#itemText").val());

    items.push( $("#menuList").val());
    
    items.push( $("#itemUrl").val());

    var success = function (data){
     // btn.button('reset');
     if(data.error > 0){
      if(itemOption =="edit"){
          $("#item_"+idMenu+"_"+idItem).html()
       }
        addItemGui(idMenu, data.error,items);
     
      }
     }

     var data =  { idmenu:idMenu, action:itemOption, table:"item","itemId" :idItem, items:items } ;
      callAjax(data,success); 
      event.preventDefault()

}


 function addItemGui(idMenu,iditem,items){

      // idMenu = event.data.idMenu
    var items = new Array();

    items.push( $("#itemType").val());
    items.push( $("#itemText").val());
    items.push( $("#menuList").val());
    items.push( $("#itemUrl").val());



      globalItemId = iditem;
        id = iditem;
        idValue = idMenu+"_"+ id
      text= $("#itemType option:selected").text();
      menu= $("#menuList option:selected").text();
      if ($("#menuList").val() == -1)
        menu = ""

      url= $("#itemUrl").val();


       var htmlValue = '<div><span aria-hidden="true"></span> '
        htmlValue += '<b>Item: </b>'+  $("#itemText").val() 
        htmlValue += '<br/><b>Tipo: </b>'+  text
        if(url!="") 
          htmlValue += '<br/><b>URL: </b>'+ url
        else
          htmlValue += '<br/><b>Menu: </b>'+ menu

        htmlValue += '</span><div>'
        htmlValue += '<button id="trash_'+idValue+'" type="button" class="pull-right btn btn-default btn-xs">'
        htmlValue +='<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'
        htmlValue +='</button>'
        htmlValue += '<button id="edit_'+idValue+'" type="button" class="pull-right btn btn-default btn-xs">'
        htmlValue +='<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>'
        htmlValue +='</button>'
        htmlValue +='</div>'
        htmlValue +='</div></li>';

       $("#itemText").val("")
        var el ;
       if(itemOption =="add"){
           el = document.createElement('li');

           $( el ,{
            'class': 'list-group-item ui-sortable-handle'
            }).appendTo("#list_"+idMenu);
                $(el).attr({id:"item_"+idValue});
         $(el).addClass("list-group-item ui-sortable-handle")

        }else{
          el = $("#item_"+idValue)
        }
       $(el).html(htmlValue)
      
       setEditItemEvent(idMenu, id,items);
       setTrashItemEvent(idMenu, id);
       showItemModal();
       event.preventDefault()


 }


function getId(preffix){
  return preffix + Math.floor((Math.random() * 100000) + 1);
}

function addMenu(name, level,anytext, id){
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
//  htmlValue+= '      <input id="txtItem_'+id+'" type="text" class="form-control"  placeholder="Nuevo item">'
  if(!anytext)
   htmlValue+= '      <a id="add_'+id+'" class="btn btn-default btn-sm" href="#"  role="button">Agregar Item</a>'
//   htmlValue+= '      <a id="save_'+id+'" class="btn btn-primary btn-sm" href="#" data-loading-text="Espere..."  role="button">Guardar</a>'
   htmlValue+= '   </div>'                
   htmlValue+= ' </div>'
  htmlValue+= '</div>'
$(el).html(htmlValue)
$(el).hide().fadeIn(500)
  event.preventDefault()
   setEvents(id)
}

  function setTrashItemEvent(idMenu,idItem){
    //globalItemId = idItem
    $("#trash_"+idMenu+"_"+idItem).click(function(){

        var r = confirm("¿Esta seguro de borrar este item?");
          if (r == true) {
            var success = function ( data){
              $("#item_"+idMenu+"_"+idItem).remove()
              event.preventDefault()
            }
            var data =  { "iditem": idItem,idmenu:idMenu, action:"delete", table:"item" } ;
              callAjax(data,success); 
            }
  })
}

  function setEditItemEvent(idMenu,idItem,item){
 // globalItemId = idItem
    $("#edit_"+idMenu+"_"+idItem).on("click",{"idMenu":idMenu},function(event){
        showItemModal();
         idMenu = event.data.idMenu;
        itemOption ="edit";
        $("#itemType").val(item[0]);
        $("#itemText").val(item[1])
        $("#menuList").val(item[2])
        $("#itemUrl").val(item[3])
     
        if($("#itemType").val() ==1){
          $("#menuInput").show();
          $("#urlInput").hide();
        }else{
          $("#menuInput").hide();
          $("#urlInput").show();
        }
        setEventsAddItem(idMenu, idItem)

        
  }) 

}


</script>
</head>

<body>
   
  <div id="modalItem" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Item del Menu</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="itemText"  class="col-sm-2 control-label">Contenido: </label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="itemText" placeholder="Item">
            </div>

          </div>

          <div id="typeInput" class="form-group">
             <label for="itemType" class="col-sm-2 control-label">Accion del Item: </label>
             <div class="col-sm-10">
                <select id="itemType" class="form-control">
                  <option value="1">Ir al Menu...</option>
                  <option value="2">Redireccionar peticion</option>
                </select>
             </div>
          </div>

          <div id="menuInput" class="form-group">
             <label for="menuList" class="col-sm-2 control-label">Ir al menu: </label>
             <div class="col-sm-10">
                <select id="menuList" class="form-control" >
                  <option value="-1">Seleccione el menu</option>
                  <?php

                    $result = $db->getMenus();
                    while($row = mysqli_fetch_array($result)) {
                      echo  "<option value='".$row["idmenu"]."'>".$row["name"]."</option>";
                    }

                  ?>
                </select>
             </div>
          </div>


          <div id="urlInput" class="form-group">
             <label for="itemUrl" class="col-sm-2 control-label">URL: </label>
             <div class="col-sm-10">
              <input type="text" class="form-control" id="itemUrl" placeholder="http://">
             </div>
          </div>

        </form>
      <div id="msg" class="alert alert-warning" role="alert"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button id="addElement" type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
 
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

<form class="form">
  <div class="form-group">
    <label for="name">Nombre del Menu: </label>
    <input type="text" class="form-control" id="name" placeholder="Nombre del Menu">
  </div>
  <div class="form-group">
    <label for="label">Pregunta a mostrar: </label>
    <input type="text" class="form-control" id="label" placeholder="Etiqueta a mostrar">
       
  </div>
  <div class="form-group">
    <input id="anyText" value="1"  type="checkbox"> Espera cualquier texto  
  </div>
  <div id="listmenu" class="form-group">
     <label for="menuList1" >Ir al menu: </label>
          
                <select id="menuList1" class="form-control" >
                  <option value="-1">Seleccione el menu</option>
                 
                </select>
    </div>  
      <br/>
        <a id="addMenu"  data-loading-text="Loading..."  class="btn btn-primary" href="javascript:return false" role="button">
          <span class="glyphicon glyphicon-plus"></span> Agregar
        </a>
            
</form>


           </div>
          
           
          </div>
       </br>
       <div id="startMenu"></div>
       <?php
        mysqli_data_seek($result, 0);
        //$result = $db->getMenus();
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
                                echo '<li id="item_'.$row["idmenu"].'_'.$item["iditem"].'"" ';
                                echo 'class="list-group-item"> <div><span>';
                                echo '<b>Item: </b> '.$item["name"];
                                if($item["type"] ==2){
                                 echo '</br><b>Tipo: </b>Redireccionar Petici&oacute;n';
                                 echo '</br><b>URL: </b> '.$item["url"];
                                }else{
                                 echo '</br><b>Tipo:</b>Ir la Menu ';
                                 echo '</br><b>Menu: </b> '.$item["menu"];
                               }
                              echo "</span>";
                                 
                                 ?>
                                   <div>
                                  

                                    <button id="trash_<?php echo $row["idmenu"]."_".$item["iditem"]?>" type="button" class="pull-right btn btn-default btn-xs">
                                      <span class="glyphicon glyphicon-trash" aria-hidden="true"> </span>
                                    </button>&nbsp;
                                     <button id="edit_<?php echo $row["idmenu"]."_".$item["iditem"]?>" type="button" class="pull-right btn btn-default btn-xs">
                                      <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </button>
                                    <script type="text/javascript">
                                    //item[0]=
                                    var items = new Array();


                                    items.push("<?php echo $item["type"]; ?>");
                                    items.push("<?php echo $item["name"]; ?>");
                                    items.push("<?php echo $item["next_menu"]; ?>");
                                    items.push("<?php echo $item["url"]; ?>");
                                    
                                    setEditItemEvent("<?php echo $row["idmenu"] ?>","<?php echo $item["iditem"] ?>", items)

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
                            <?php
                              if($row["anytext"]!=1) {
                            ?> 
                              <a id="<?php echo "add_".$row["idmenu"] ?>"  class="btn btn-default btn-sm" href="#" role="button">Agregar Item</a>
                            <?php } ?>
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
          addMenu($("#name").val(),$("#label").val(),$("#anyText").is(':checked'), data.error);
          btn.button('reset');
          $('#menuList').append($('<option>', {
           value: data.error,
           text: $("#name").val()
          }));
            $("#listmenu").hide()
           $("#name").val("");
           $("#label").val("");
           $("#anyText").prop( "checked", false );
          $("#menuList1").val();
           //TODO



      };  

      var data =  { name: $("#name").val(),
      "goto":$("#menuList1").val(),
      anytext:$("#anyText").is(':checked'), 
      label: $("#label").val(), 
      action:"add", table:"menu" } ;
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