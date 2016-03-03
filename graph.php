<?php
    session_start();

  if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: login.php");
    include("cgi/config.php"); 
    include("cgi/LocalDB.php"); 


     $db = new LocalDB;
     $db->connect($HOST, $DATABASE, $USER, $PASSWORD);

   $idcode= $_SESSION['idclient'];
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
     <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
 <link href="js/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link href="css/estilo.css" rel="stylesheet">

    
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/bootstrap/js/moment.js"></script>
     <script src="js/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
     <script src="js/bootstrap/js/Chart.js"></script>
    <script src="js/drag.js"></script>
    <style type="text/css">
.line-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;

} 

.pie-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;

} 

.line-legend,.pie-legend{

    list-style-type: none;
}
    </style>
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
           <li class="active"><a href="graph.php">Gr&aacute;ficos</a></li>
            <li><a href="test.php">Test</a></li>        
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h3 class="page-header">Gr&aacute;ficos</h3>
      
        <div id="formRow" class="row"> 
        <form class="form-inline"> 
           <div class="form-group">
             <label for="initDate">Fecha Inicial: </label>
                <input type='text' class="form-control" id='initDate' />
                <script type="text/javascript">
               $(function () {
                    $('#initDate').datetimepicker({
                    defaultDate: moment().subtract(7, 'days'),format: 'YYYY-MM-DD HH:mm:ss'
                  });
                  });
                </script>
           </div>
           <div class="form-group">
             <label for="endDate"> Fecha Final: </label>
                <input type='text' class="form-control" id='endDate' />
                <script type="text/javascript">
              
                $(function () {
                    $('#endDate').datetimepicker({
                    defaultDate: moment(),
                    format: 'YYYY-MM-DD HH:mm:ss',
                    useCurrent: false
                  });
                  });
                  
                </script>
           </div>
            <div class="form-group">         
               <a id="search" class="btn btn-primary" data-loading-text="Buscando..." href="#" role="button">Buscar</a>
           </div>
      </form>
      </div>
     
       <div id="graphs" class="row"></div>
        <br></br>
      </div>
    </div>

    <!-- /.container -->

<script>
  $( document ).ready(function() {

        
        $("#initDate").on("dp.change", function (e) {
            $('#endDate').data("DateTimePicker").minDate(e.date);
        });
        $("#endDate").on("dp.change", function (e) {
            $('#initDate').data("DateTimePicker").maxDate(e.date);
        });


        $("#search").click(function (){
          var $btn = $(this).button('loading')
            $("#graphs").html("</br>");
            generateGraph();
        })

        Chart.defaults.global.responsive = true;  
        generateGraph();

    });

function generateGraph(){
  fechaI = $("#initDate").val();
  fechaF = $("#endDate").val();
  var success = function (data){
     // btn.button('reset');
      addElements(data);
      success = function (data){
      // btn.button('reset');
        paintLineGraph(data);
      }
      var data =  {  idclient:"1",table:"line", action:"query",fechaIni: fechaI, fechaFin: fechaF } ;
      callAjax(data,success); 
  

    }
     var data =  {  idclient:"1",table:"pie", action:"query",fechaIni: fechaI, fechaFin: fechaF } ;
       callAjax(data,success); 
  
 


}


function getLineElement(name, value, index){
colors = ["rgba(212, 226, 251,1)", "rgba(189, 140, 177,1)","rgba(43, 126, 181,1)",
"rgba(43, 163, 181,1)","rgba(68, 212, 133,1)","rgba(171, 203, 214,1)","rgba(72, 114, 128,1)" ]
 
var line =  {
            label: name,
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: colors[index],
            pointColor: colors[index],
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: colors[index],
            data: value
        }

  return line;

}

function paintLineGraph(data){


for(i = 0; i < data.datasets.length; i++){
    //for(j = 0; j < data[i].op.length; j++){
  j=0;
  id ="line_"+i+"_"+j;
  
  var dataset=[];
  for(j = 0; j < data.datasets[i].rows.length; j++){
      dataset.push(getLineElement(data.datasets[i].rows[j].label,data.datasets[i].rows[j].data,j))
  }
  var data1 = {
    labels: data.dates,
    datasets:dataset
  };
   var ctx = document.getElementById(id).getContext("2d");
  datasets=dataset 
  var options = {
   legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend list-inline\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

  }
      
     var myNewChart = new Chart(ctx).Line(data1,options);
       var legend = myNewChart.generateLegend();
        $("#line"+i).append(legend);
  }

 $("#search").button('reset');

}

function addElements(data){

  /*
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Panel heading</div>
  <div class="panel-body">
    <p>...</p>
  </div>
  */

  var id =0;
  var html =""
  j=0;
 for(i = 0; i < data.length; i++){
   html += "<br/><div class='panel panel-primary'>";
   html += '<div class="panel-heading">'+data[i].q+'</div>'
   html +='<div  class="panel-body">'

 // for(j = 0; j < data[i].op.length; j++){
   html +="<div class='row'>"
   html +='<div id="content'+i+'" class="col-xs-6">'
   html +='<canvas style="display: inline-block;" id="pie_'+i+'_'+j+'"  ></canvas>'
   html +='</div>'
   html +='<div id="line'+i+'" class="col-xs-6">'
   html +='<canvas style="display: inline-block;" id="line_'+i+'_'+j+'" ></canvas>'
   html += "</div>";
   html += "</div>";
   id++
  //}
    html += '</div>' 
    html += '</div>' 
  }  
  $("#graphs").html(html)
  
  id=0;


  for(i = 0; i < data.length; i++){
    //for(j = 0; j < data[i].op.length; j++){
        id ="pie_"+i+"_"+j;
        var ctx = document.getElementById(id).getContext("2d");
        datasets = data[i].op;
var options = {
   legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend list-inline\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

}
        var myNewChart = new Chart(ctx).Pie(datasets,options);
        var legend = myNewChart.generateLegend();
        $("#content"+i).append("<div class='row  center-block'><div class='col-xs-12'>"+legend+"</di></div>");
  }
 


}

function callAjax(data, onSucces){

  var posting = $.post( "cgi/api.php", data);
  posting.done(function( data ) {
   // if(data.error>0){
    //  onSucces(data);
     
      

      onSucces(data);
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
     var data =  { idcode:"<?php echo $idcode ?>", idclient:"1",table:transaction, action:"test", command:usercommand, mobile:usermobile } ;
       callAjax(data,success); 
      event.preventDefault()

})

$("#btnCancel").click(function(){

   var btn = $(this).button('loading')
    var transaction ="BEGIN"
    var success = function (){
     btn.button('reset');
      //$("#response").html(val);
    }
    var data =  { idcode:"<?php echo $idcode ?>", idclient:"1",table:transaction, action:"test", command:usercommand, mobile:usermobile } ;
       callAjax(data,success); 
      event.preventDefault()   
    

})



</script>


</body>

</html>