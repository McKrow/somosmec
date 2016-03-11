<?php
session_start();
header('Content-Type: text/xml');
$request_body= file_get_contents('php://input');
include_once("config.php"); 
include("LocalDB.php");  

$xml = new SimpleXMLElement($request_body);

$myfile = fopen("newfile.txt", "w");
fwrite($myfile, "\n".$xml->asXML());

//$sesion= session_id();
if(isset($xml["type"])){	

	$db = new LocalDB;
	$db->connect($HOST, $DATABASE, $USER, $PASSWORD);

	switch ($xml["type"]) {
		case 'Begin':
			$sesion = session_id();
			$ussdcode = (string)$xml->processUnstructuredSSRequest_Request["string"];
			$number = (string)$xml->processUnstructuredSSRequest_Request->msisdn["number"];
			$_SESSION[$sesion]["number"] = $number;
			$_SESSION[$sesion]["ussdcode"] = $ussdcode;
			
			$_SESSION[$sesion]["invokeId"] = $xml->processUnstructuredSSRequest_Request["invokeId"];


			$_SESSION[$sesion]["interaction"] = 1;
			$rows = $db->getMenuInteraction($_SESSION[$sesion]["interaction"]
				,$_SESSION[$sesion]["ussdcode"]);
			
			$menu = getMenuOptions($rows);
			//echo $menu;
			$outXML = createXmlResponse($menu, "Continue", $xml, $sesion );
			echo "$outXML";
			fwrite($myfile, "\n".$outXML);
			//var_dump($xml->processUnstructuredSSRequest_Request->msisdn[number]);
			break;
		case 'Continue':
			$sesion = str_replace("Session Id : ","",$xml["userObject"]);
			$ussdcode = (string)$xml->unstructuredSSRequest_Response["string"];
			$number = (string)$xml->unstructuredSSRequest_Response->msisdn["number"];
			//getItemInteraction($_SESSION["interaction"], $_SESSION["ussd"], $_POST["command"]);
			$item= $db->getItemInteraction($_SESSION[$sesion]["interaction"], 
				$_SESSION[$sesion]["ussdcode"], $ussdcode);
			
			
			$db->addTransaction ($number, $_SESSION["menu"], $_SESSION[$sesion]["ussdcode"]
								,$item, $ussdcode,$session);

			
			$_SESSION[$sesion]["interaction"] = $_SESSION[$sesion]["interaction"] + 1;

			$rows = $db->getMenuInteraction($_SESSION[$sesion]["interaction"] ,
				$_SESSION[$sesion]["ussdcode"], $ussdcode);
			
			$menu = getMenuOptions($rows);
			//echo $menu;
			// consultamos si hay una segunda tarea
			$rows = $db->getMenuInteraction($_SESSION[$sesion]["interaction"] + 1,$_SESSION[$sesion]["ussdcode"]);
			$op="Continue";	
			if($rows->num_rows <= 0)
				$op ="End";
			
			$outXML = createXmlRequestResponse($menu, $op, $xml,$sesion );
			echo "$outXML";
			fwrite($myfile, "\n".$outXML);
			break;

		case 'End':

			break;
		case 'Abort':
			session_destroy();
		default:
			# code...
			break;
	}
	fclose($myfile);
	$db->close();
}



function getMenuOptions($info){

	$str = "";
	$i=0;
	$menuname = "";
	while($item = mysqli_fetch_array($info)) {
		$_SESSION["menu"] = $item["label"];
		$menuname = $item["label"];
   		$coma ="";
   		if($i!=0)
   			$coma ="\n";
   		$str .=  $str.$coma.$item["itemname"];
		$i++;
     }

    return $menuname."\n".$str;
}


function createXmlResponse($response, $type, $xml, $session ){
	$xml["type"] = $type;
	$xml["userObject"]="Session Id : $session";
	if($type =="End")
		$xml->addAttribute("prearrangedEnd","false");
	//$xml["unstructuredSSRequest_Request"] = $xml->processUnstructuredSSRequest_Request;
	$xml->addChild("unstructuredSSRequest_Request" , $xml->processUnstructuredSSRequest_Request);
	$xml->unstructuredSSRequest_Request->addAttribute("invokeId",$xml->processUnstructuredSSRequest_Request["invokeId"]);
	$xml->unstructuredSSRequest_Request->addAttribute("dataCodingScheme",$xml->processUnstructuredSSRequest_Request["dataCodingScheme"]);

	unset($xml->processUnstructuredSSRequest_Request);
	//$arrayName = array('' => , );

	$xml->unstructuredSSRequest_Request["string"] = $response;
	//7$xmlO = new SimpleXMLElement('<dialog/>');
	//array_walk_recursive($xml, array ($xmlO, 'addChild'));
	//array_to_xml((array)$xml, $xmlO);
	// ArrayToXML::toXml( $xml, "", $xmlO );
	return $xml->asXML();
}

function createXmlRequestResponse($response, $type, $xml, $session ){
	$xml["type"] = $type;
	$xml["userObject"]="Session Id : $session";
	
	if($type =="Continue"){
		
		$xml->addChild("unstructuredSSRequest_Request" , $xml->unstructuredSSRequest_Response);
		$xml->unstructuredSSRequest_Request->addAttribute("invokeId",$xml->unstructuredSSRequest_Response["invokeId"]);
		$xml->unstructuredSSRequest_Request->addAttribute("dataCodingScheme",$xml->unstructuredSSRequest_Response["dataCodingScheme"]);
		$xml->unstructuredSSRequest_Request["string"] = $response;
	
	}else if ($type=="End"){
		
		$xml->addAttribute("prearrangedEnd","false");
		

		$xml->processUnstructuredSSRequest_Response->addAttribute("invokeId",$_SESSION[$session]["invokeId"]);
		
		//$xml["processUnstructuredSSRequest_Response"] = $xml->unstructuredSSRequest_Response;
		$xml->addChild("processUnstructuredSSRequest_Response" , $xml->unstructuredSSRequest_Response);
		$xml->processUnstructuredSSRequest_Response->addAttribute("invokeId",$xml->unstructuredSSRequest_Response["invokeId"]);
		$xml->processUnstructuredSSRequest_Response->addAttribute("dataCodingScheme",$xml->unstructuredSSRequest_Response["dataCodingScheme"]);
		$xml->processUnstructuredSSRequest_Response["string"] = $response;
		
	
	}
		unset($xml->unstructuredSSRequest_Response);
	
	//$arrayName = array('' => , );

	//$xml->processUnstructuredSSRequest_Response["string"] = $response;
	//7$xmlO = new SimpleXMLElement('<dialog/>');
	//array_walk_recursive($xml, array ($xmlO, 'addChild'));
	//array_to_xml((array)$xml, $xmlO);
	// ArrayToXML::toXml( $xml, "", $xmlO );
	return $xml->asXML();
}


/*



<?xml version="1.0" encoding="UTF-8" ?>
<dialog type="End" appCntx="networkUnstructuredSsContext_version2" networkId="0" localId="7" remoteId="2" mapMessagesSize="1" prearrangedEnd="false" returnMessageOnError="false" userObject="Session Id : 6B3846EDCAD74FB015D53276134063A2">
.<localAddress pc="2" ssn="8">
..<ai value="83"/>
..<gt type="GlobalTitle0100" tt="0" es="2" np="1" nai="4" digits="9960639901"/>
.</localAddress>
.<remoteAddress pc="0" ssn="8">
..<ai value="18"/>
..<gt type="GlobalTitle0100" tt="0" es="2" np="1" nai="4" digits="9960639902"/>
.</remoteAddress>
.<destinationReference number="222222" nai="international_number" npi="ISDN"/>
.<originationReference number="111111" nai="international_number" npi="ISDN"/>
.<processUnstructuredSSRequest_Response invokeId="0" dataCodingScheme="15" string="Thank You!"/>
</dialog>



<?xml version="1.0" encoding="UTF-8" ?>
<dialog type="Begin" appCntx="networkUnstructuredSsContext_version2" networkId="0" localId="6" remoteId="1" mapMessagesSize="1" returnMessageOnError="false">
.<localAddress pc="2" ssn="8">
..<ai value="83"/>
..<gt type="GlobalTitle0100" tt="0" es="2" np="1" nai="4" digits="9960639901"/>
.</localAddress>
.<remoteAddress pc="0" ssn="8">
..<ai value="18"/>
..<gt type="GlobalTitle0100" tt="0" es="2" np="1" nai="4" digits="9960639902"/>
.</remoteAddress>
.<destinationReference number="222222" nai="international_number" npi="ISDN"/>
.<originationReference number="111111" nai="international_number" npi="ISDN"/>
.<processUnstructuredSSRequest_Request invokeId="0" dataCodingScheme="15" string="*519#">
..<msisdn number="9960639901" nai="international_number" npi="ISDN"/>
.</processUnstructuredSSRequest_Request>
</dialog>
*/

//echo $xml["type"];

?>