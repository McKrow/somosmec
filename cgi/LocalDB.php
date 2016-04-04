<?php

class LocalDB {
	
	//instancia de la conexion
	private $connection;

	// Funcion para conectar a la BD
	function connect($host,$database, $user, $password){
		$this->connection = mysqli_connect($host,$user,$password,$database); 
		if (mysqli_connect_errno()) {
 				 echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}


	function getUserCodes($iduser){

		$sql = "select * from ussd_codes where idclient = ".$iduser;
		return mysqli_query( $this->connection,$sql);
	}

	function validateUser($user, $password){
		$strSQL =  'select idclient from users where email =? and password= sha1(?)';
		$stmt =  mysqli_prepare($this->connection,$strSQL);
		mysqli_stmt_bind_param($stmt,"ss", $user,$password);
	
		mysqli_stmt_execute($stmt);

		 mysqli_stmt_bind_result($stmt, $idclient);
		// mysqli_stmt_bind_result($stmt);
		while (mysqli_stmt_fetch($stmt)) {
		//while ($row = $result->fetch_assoc()) {
			return $idclient;//$row["idclient"];
    	}
	}

	// Funcion para cerrar la conexion a la base de Datos
	function close(){
		mysqli_close($this->connection);
	}

	function getMenus(){
		
		$strSQL = "select * from menu order by added_date desc";
		return mysqli_query( $this->connection,$strSQL);
	}


	function getItemsByMenuId($idMenu){
		
		$strSQL = "select a.*, type, b.name menu from item a left join menu b on (next_menu = b.idmenu) ";
		$strSQL .= " where a.idmenu = $idMenu";
		error_log( "$strSQL",0);
		return mysqli_query( $this->connection,$strSQL);
	}


	function addMenu($name, $label, $anyText, $goto){
		$name = addslashes($name);
		$label =  addslashes($label);
		$sql = "INSERT INTO menu (name, label, anytext, goto) values ('$name','$label',$anyText,$goto)";
		if(mysqli_query( $this->connection,$sql)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_insert_id($this->connection);
		}else{
			error_log( "$strSQL, Falla insertar programacion: " . mysqli_error($this->connection),0);
			return 0;
		}

	}



	function addItem($idmenu, $name,$orden, $type, $url, $nextmenu){
		$name = addslashes($name);
		$label =  addslashes($label);
		$sql = "INSERT INTO item (name, idmenu, type, next_menu, url, orden) ";
		$sql .="values ('$name','$idmenu', $type, $nextmenu, '$url', $orden)";
		if(mysqli_query( $this->connection,$sql)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_insert_id($this->connection);
		}else{
			error_log( "$strSQL, Falla insertar programacion: " . mysqli_error($this->connection),0);
			return 0;
		}

	}


	function updateItem($idmenu,$iditem, $name,$orden, $type, $url, $nextmenu){
		$name = addslashes($name);
		$label =  addslashes($label);
		$sql = "UPDATE  item set name = '$name', type=$type, next_menu=$nextmenu, url='$url', orden = $orden ";
		$sql .="where iditem= $iditem";
		if(mysqli_query( $this->connection,$sql)===TRUE){
			//error_log( "$sql: ",mysqli_affected_rows($this->connection));
			return mysqli_affected_rows($this->connection);
		}else{
			//error_log( "$sql, Falla insertar programacion: " . mysqli_error($this->connection),0);
			return 0;
		}

	}


	function addTree($idCode, $idMenu, $orden){
		$idCode = addslashes($idCode);
		$idMenu =  addslashes($idMenu);
		$sql = "INSERT INTO tree (idcode, idmenu, orden) values ('$idCode','$idMenu', $orden)";
		if(mysqli_query( $this->connection,$sql)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_insert_id($this->connection);
		}else{
			error_log( "$strSQL, Falla insertar tree: $sql " . mysqli_error($this->connection),0);
			return 0;
		}

	}

	function addTransaction ($msisdn,$menu, $ussd , $command, $label, $session,$idmenu, $iditem){

	$sql = "INSERT INTO transactions (msisdn,menu, ussd, command, label, sessionId,idmenu, iditem) ";
	$sql .= "values ('$msisdn', '$menu', '$ussd','$command', '$label', '$session',$idmenu, '$iditem')";
		if(mysqli_query( $this->connection,$sql)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_insert_id($this->connection);
		}else{
			error_log( "Falla insertar transactions: $sql " . mysqli_error($this->connection),0);
			return 0;
		}

	}

	//Funcion para borrar el detalle de mensajes
	function deleteTreeByClient($idcode){

		$strSQL = "DELETE FROM tree WHERE idcode = $idcode";
		
		if(mysqli_query( $this->connection,$strSQL)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_affected_rows($this->connection);
		}else{
			error_log( "$strSQL, Falla  borrar detalle: " . mysqli_error($this->connection),0);
			return 0;
		}

	}



	//Funcion para borrar el detalle de mensajes
	function deleteItemByIdMenu($idmenu){

		$strSQL = "DELETE FROM item WHERE idmenu = $idmenu";
		
		if(mysqli_query( $this->connection,$strSQL)===TRUE){
			//error_log( "$strSQL",0);
			return mysqli_affected_rows($this->connection);
		}else{
			error_log( "$strSQL, Falla  borrar detalle: " . mysqli_error($this->connection),0);
			return 0;
		}

	}


	function getFinalMenu(){
		$strSQL = "select a.*, b.idMenu as final from menu a  left join  tree b on (a.idmenu = b.idmenu) order by orden asc";
		return mysqli_query( $this->connection,$strSQL);
	}


	function getMenuList($ussd, $dateinit, $dateEnd){
		$sql = 'select distinct b.idmenu, b.name, b.label label from 
transactions a, menu b
where a.idmenu = b.idmenu and ussd= "'.$ussd.'" and a.event_date between "'.$dateinit.'" AND "'.$dateEnd.'"';
	//	$sql .= "group BY command, menu, DATE_FORMAT(event_date, '%d-%m-%Y')";
		error_log( "$sql,Consulta menu: " . mysqli_error($this->connection),0);
		return mysqli_query( $this->connection,$sql);
	}


	function getSummaryByMenu($ussd, $menu, $dateinit, $dateEnd){
		$sql = "select  count(1) value, command  from transactions  where command is not null ";
		$sql .= "and ussd= '$ussd' and idmenu ='$menu' and  event_date between '$dateinit' AND '$dateEnd' ";
		$sql .= "group by command";
		error_log( "$sql",0);
		return mysqli_query( $this->connection,$sql);
	}

	function getSummaryByDate($ussd,$dateIni, $dateFin){
		$sql =  'select   DATE_FORMAT(event_date, "%d-%m-%Y") fecha , idmenu, command , count(command) cant, iditem
				from transactions  where command is not null 
				and ussd= "'.$ussd.'"  
				and  event_date between "'.$dateIni.'" AND "'.$dateFin.'" 
				group by  DATE_FORMAT(event_date, "%d-%m-%Y") ,idmenu, command  ,iditem
				order by event_date asc';
		error_log( "getSummaryByDate: $sql",0);
		return mysqli_query( $this->connection,$sql);
		

	}


	function getMenuByText($idMenu, $text){
		$sql="select * from item where idmenu = $idMenu and instr(name,'$text')=1";
		error_log( "$sql",0);
		return mysqli_query( $this->connection,$sql);		
	}

	function getMenuInteraction($idmenu , $ussdcode){
		$strSQL = 'select c.goto, c.idmenu, iditem, c.name, c.label, d.name as itemname  from ussd_codes a
				inner join tree b on (b.idcode = a.idcode)
				inner join menu c on (b.idmenu = c.idmenu)
				left join item d on (d.idmenu = c.idmenu)
				where  code = "'.$ussdcode.'" '; 

			if($idmenu!=""){
				$strSQL .=" and c.idmenu =  $idmenu ";
			}
			$strSQL .= " order by d.name asc";	
		error_log( "$strSQL",0);
		return mysqli_query( $this->connection,$strSQL);
	}

	function getMenuInteractionByIDMenu($idmenu){
		$strSQL = 'select a.idmenu, iditem, a.name, 
			a.label, b.name as itemname, 
			type, next_menu, url
			from menu a left join item b on (a.idmenu = b.idmenu)
			where a.idmenu ='.$idmenu .' order by b.name asc';
			
			
		error_log( "$strSQL",0);
		return mysqli_query( $this->connection,$strSQL);
	}


	function getDateLabels($menu, $ussd, $dateinit, $dateEnd){
		$sql = "select  distinct  DATE_FORMAT(event_date, '%d-%m-%Y') label from transactions  where command is not null ";
		$sql .= "and ussd= '$ussd' and menu ='$menu' and  event_date between '$dateinit' AND '$dateEnd' ";
		$sql .= "group by command";
		//error_log( "$sql",0);
		return mysqli_query( $this->connection,$sql);
	}

	function getItemInteraction($orden, $ussdcode, $itemcount){
		$strSQL = 'select c.name, c.label, d.name as itemname  from ussd_codes a
				inner join tree b on (b.idcode = a.idcode)
				inner join menu c on (b.idmenu = c.idmenu)
				left join item d on (d.idmenu = c.idmenu)
				where  code = "'.$ussdcode.'" and b.orden = '. $orden .' and d.orden =  '. $itemcount;
				error_log( "$strSQL",0);
		$result = mysqli_query( $this->connection,$strSQL);
		while($row = mysqli_fetch_array($result)) {
		 	return $row["itemname"];		 	
	 	}
	 	return '';
	}


//Funcion para borrar el un menu
	function deleteMenuById($idmenu){

		$strSQL = "DELETE FROM menu WHERE idmenu = $idmenu";
		
		if(mysqli_query( $this->connection,$strSQL)===TRUE){
			//error_log( "$strSQL",0);

			return mysqli_affected_rows($this->connection);
		}else{
			error_log( "$strSQL, Falla  borrar detalle: " . mysqli_error($this->connection),0);
			return 0;
		}

	}


//Funcion para borrar el un menu
	function deleteItemById($iditem){

		$strSQL = "DELETE FROM item WHERE iditem = $iditem";
		
		if(mysqli_query( $this->connection,$strSQL)===TRUE){
			//error_log( "$strSQL",0);

			return mysqli_affected_rows($this->connection);
		}else{
			error_log( "$strSQL, Falla  borrar detalle: " . mysqli_error($this->connection),0);
			return 0;
		}

	}

	function getAllMenuItem($ussdcode, $menu){
		$sql = 'select d.name from item d 
		where  d.idmenu ="'.$menu.'"';
		error_log( "$sql",0);
		return mysqli_query( $this->connection,$sql);
	}

}
?>