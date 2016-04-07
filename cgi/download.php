<?php
session_start();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_ussd.csv');

include_once("config.php"); 
include("LocalDB.php"); 

if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: ../login.php");

	$ussdcode = $_SESSION["ussd"];
	//$output = fopen('php://output', 'w');
   
if(isset($_GET)){
	$db = new LocalDB;
	$db->connect($HOST, $DATABASE, $USER, $PASSWORD);
	//fputcsv($output, array('Id Sesion', 'Fecha de Evento', 
	//	'Telefono', 'Menu', 'Id de menu', 'Item', 'Id de item'));

	echo "Id Sesion, Fecha de Evento, telefono, menu, id de menu, item, id de item \n";
	//echo "sessionId, event_date fecha, msisdn, menu , idmenu, command,iditem "
	$result = $db->getTransactionByDate($ussdcode ,$_GET["fechaI"] , $_GET["fechaF"]);
	while($row = mysqli_fetch_array($result)) {

		echo $row["sessionId"].",".$row["fecha"].",\"".$row["msisdn"]."\",\"".$row["menu"]."\",".$row["idmenu"].",\"".$row["command"]."\",". $row["iditem"]."\n";

	}
	$db->close();
}

?>