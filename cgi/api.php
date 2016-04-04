<?php
session_start();
header('Content-Type: application/json');

include_once("config.php"); 
include("LocalDB.php"); 

if(!isset($_SESSION['user']) && $_SESSION['user']=="")
    header("Location: ../login.php");

$ussdcode = $_SESSION["ussd"];

 $action = $_POST["action"];
 $highlight = array("#F7464A", "#46BFBD", "#FDB45C","FFC870");
$color  = array( "#FF5A5E","#5AD3D1","#FFC870","#FDB45C");
   
if(isset($_POST)){
	$db = new LocalDB;
	$db->connect($HOST, $DATABASE, $USER, $PASSWORD);

	$val=0;
	$desc = "";
	switch($action){

		case "add":
			$table = $_POST["table"];
			switch($table){
				case "menu":
					$val=0;
					$goto=0;
					if($_POST["anytext"] =="true")
						$val=1;
					if(isset($_POST["goto"]))
						$goto=$_POST["goto"];

					$val = $db->addMenu($_POST["name"], $_POST["label"],$val,$goto);
					if($val == 0)
					$desc = "Registro ingresado correctamente";
					printOuput($val, $desc);
				break;
				case "item":
					// Borramos los item actual
					//if(count($_POST["items"]) > 0){
					//		 $db->deleteItemByIdMenu($_POST["idmenu"]);
					//}
				/*
items.push( $("#itemType").val());
    items.push( $("#itemText").val());
    items.push( $("#menuList").val());
    items.push( $("#itemUrl").val());
				*/
					$i = 0;
					//foreach ($_POST["items"] as &$value) {
						$i++;
					    $val = $db->addItem($_POST["idmenu"],$_POST["items"][1]
					    	, $i, $_POST["items"][0], $_POST["items"][3], $_POST["items"][2]);
					  //  addItem($idmenu, $name,$orden, $type, $url, $nextmenu)
					//}
					$desc = "Items actualizado exitosamente";
					printOuput($val, $desc);
				break;

				case "tree":

					if(count($_POST["items"]) > 0){
							 $db->deleteTreeByClient($_POST["idcode"]);
					}

					$i = 0;
					foreach ($_POST["items"] as &$value) {
						$i++;
					
					    $val= $db->addTree($_POST["idcode"], $value, $i);
					    $val++;
					}
					$desc = "Tree actualizado extisamente";
					printOuput($val, $desc);
				break;

				
				default:
					$val =0;
					$desc = "Parametros invalidos";
					printOuput($val, $desc);
				break;
			}
		
		break;
		case "edit":
			$table = $_POST["table"];
			switch ($table) {
				case 'item':
					 //updateItem($idmenu,$iditem, $name,$orden, $type, $url, $nextmenu)
					
					$val = $db->updateItem($_POST["idmenu"],$_POST["itemId"],$_POST["items"][1], 
										0 , $_POST["items"][0], $_POST["items"][3], 
										$_POST["items"][2]);

					
					$val = $_POST["itemId"];
					printOuput($val, "Actilizacion ejecutada con exito");
					break;
				
				default:
					# code...
					break;
			}
			break;
		case "delete":
			$table = $_POST["table"];
			switch($table){	
				case "menu":
				 	$val = $db->deleteMenuById($_POST["idmenu"]);
					$desc = "Registro borrado correctamente";
					printOuput($val, $desc);
				break;
				case "item":
				 	$val = $db->deleteItemById($_POST["iditem"]);
					$desc = "Registro borrado correctamente";
					printOuput($val, $desc);
				break;
			}
		break;
		case "query":
			$table = $_POST["table"];
			switch ($table) {
				case 'pie':
					$resp = "[";
					$question = "";
					$result = $db->getMenuList($ussdcode ,$_POST["fechaIni"] , $_POST["fechaFin"]);
					$i=0;
					while($item = mysqli_fetch_array($result)) {

						$coma=",";
						if($i==0)
							$coma="";

						$i++;
						$resp.= $coma.'{"q":"'.$item["name"]." - ".$item["label"].'","op":[' ;
						
						$options = $db->getSummaryByMenu($ussdcode , $item["idmenu"],$_POST["fechaIni"] , $_POST["fechaFin"]);
						$j=0;
						while($op = mysqli_fetch_array($options)) {
								$coma2=",";
								if($j==0)
	 								$coma2="";
	 							
	 							$resp .= $coma2.getJsonPie($op["value"], $op["command"], $j-1);
	 							$j++;

							}
						$resp.="]";

						
						$resp.="}";
					}
					$resp .= "]";

					echo $resp;

					break;
				case "line":
					$result = $db->getSummaryByDate($ussdcode ,$_POST["fechaIni"] , $_POST["fechaFin"]);
					$fechas = array();
					$menus = array();
					$opciones = array();
					$all = array();
					$all1 = array();
					
					while($item = mysqli_fetch_array($result)) {
						array_push($fechas,$item["fecha"]);
						array_push($menus,$item["idmenu"]);
						array_push($opciones,$item["command"]);
						//$all[$item["fecha"]=>$item["menu"]];
						//array_push($all,array($item["fecha"]=> array($item["menu"]=>array($item["command"]=>$item["cant"]))));
						$all1[$item["fecha"]][$item["idmenu"]][$item["command"]] = $item["cant"];
						array_push($all,array($item["fecha"]=> array($item["idmenu"]=>array($item["command"]=>$item["cant"]))));
					}
					$fechas = array_unique($fechas);
					
					$menus = array_unique($menus);
					$opciones = array_unique($opciones);
					//print_r($all);
					//print_r($all1);
					//print_r($opciones);
					$output="";
					$question =  array( );
					while (list($key1, $value1) = each($fechas)) {
						//print_r($value1);
						reset($menus);
						while (list($key2, $value2) = each($menus)) {
							
							$allItem = $db->getAllMenuItem($ussdcode , $value2);
							while($item = mysqli_fetch_array($allItem)) {
								if(!isset($question[$value2][$item["name"]]))
									$question[$value2][$item["name"]] = array();
								if(isset($all1[$value1][$value2][$item["name"]])){
									//$question[$value2][$item["name"]] = $all1[$value1][$value2][$item["name"]];
									array_push($question[$value2][$item["name"]], $all1[$value1][$value2][$item["name"]]);
								}else{
									array_push($question[$value2][$item["name"]],"0");
								}
								//array_push($question,$item["name"]);
							}

							//print_r( $question);
							//$output .= json_encode($question);
							//reset($opciones);
							
						}

						

						}
					//	print_r($question);
						$output="{";
						$output .= "\"dates\":[\"".implode("\",\"", $fechas)."\"]";
						$output .=",\"datasets\":[";
						$sep ="";
						while(list($key1, $value1)  = each($question)){
							$output .=$sep."{";
							$output .= "\"q\":\"$key1\",";
							$output .= "\"rows\":[";
							$sep2 ="";
							//reset($question[$key1]);
							//print_r( $question[$value1]);
							while(list($key2, $value2) = each($question[$key1])){
								
								$output .=$sep2."{";
								$output .= "\"label\":\"$key2\",";
								$output .= "\"data\":[";
									if(isset($question[$key1][$key2]))
										$output .= "\"".implode("\",\"", $question[$key1][$key2])."\"";
								$output .="]";
								$output.="}";
								$sep2 =",";

							}
							$output .="]";
							$output .="}";

							$sep=",";

						}
						$output.="]";
						$output.="}";


						echo $output;


    					
					
				break;	
				default:
					# code...
					break;
			}

			//case ""
		break;
		
		case "test":
			$table = $_POST["table"];

			switch ($table){
				case "BEGIN":
					$_SESSION["transactionId"] = 1;
					$_SESSION["interaction"] = 1;
					$_SESSION["ussd"] = $_POST["command"];
					$_SESSION["sesion"] = gen_uuid();
					$rows = $db->getMenuInteraction("",$_SESSION["ussd"]);
					getMenuOptions($rows);
				break; 
				case "CONTINUE":

				//		$db->getItemInteraction($_SESSION["interaction"], $_SESSION["ussd"], $_SESSION["idmenu"],$_POST["command"]);

				//	$_SESSION["interaction"] = ($_SESSION["interaction"] +1 );
					if(isset($_SESSION["goto"]) && $_SESSION["goto"] >0)
						$_POST["command"]="";

					$opts = $db->getMenuByText($_SESSION["idmenu"], 
							$_POST["command"]);
					while($item = mysqli_fetch_array($opts)) {
						$_SESSION["itemname"] = $item["name"];
						$_SESSION["iditem"] = $item["iditem"];
						$_SESSION["type"] = $item["type"];
						if(isset($item["goto"]) && $item["goto"]>0)
							$_SESSION["next_menu"] = $item["goto"];
						else
							$_SESSION["next_menu"] = $item["next_menu"];
   					}

   					$db->addTransaction ($_POST["mobile"], $_SESSION["menu"], 
   						$_SESSION["ussd"], $_SESSION["itemname"], $_POST["command"],
   					 	$_SESSION["sesion"],$_SESSION["idmenu"],$_SESSION["iditem"]);
					
   					if($_SESSION["type"] ==1 && isset($_SESSION["next_menu"])){
   						$rows = $db->getMenuInteractionByIDMenu($_SESSION["next_menu"]);
						getMenuOptions($rows);

   					}


					//$item= $db->getItemInteraction($_SESSION["interaction"], $_SESSION["ussd"], $_POST["command"]);
					// //$item, $_POST["command"], $_SESSION["sesion"]);
					//$_SESSION["interaction"] = ($_SESSION["interaction"] +1 );
					

				break;
				case "END":
					unset($_SESSION["interaction"]);
					unset($_SESSION["ussd"]);
					unset($_SESSION["sesion"]);
					unset($_SESSION["menu"]);
				break;
			}


		break;
		default:

		break;

	}

	$db->close();
}



function getJsonPie($value, $label, $index){

	global $color;
	global $highlight;
	return ' {
        "value": '.$value.',
        "color":"'.$color[$index].'",
        "highlight": "'.$highlight[$index].'",
        "label": "'.$label.'"
    }';
}



function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function getMenuOptions($info){

	$str = "";
	$i=0;
	$menuname = "";
	while($item = mysqli_fetch_array($info)) {
		$_SESSION["menu"] = $item["label"];
		$_SESSION["idmenu"] = $item["idmenu"];
		$_SESSION["next_menu"] = $item["goto"];
		$_SESSION["goto"] = $item["goto"];
		$menuname = $item["label"];
   		$coma ="";
   		if($i!=0)
   			$coma =",";
   		$str =  $str . $coma.'{"op":"'.($i+1).'","name":"'.$item["itemname"].'"}';
		$i++;
     }

     echo '{ "label":"'.$menuname.'", "items":[ '.$str. ']}';
}

function printOuput($val, $desc){
		echo '{"error":"'.$val.'", "desc":"'.$desc.'"}';
}

?>