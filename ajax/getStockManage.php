<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('stock_manage',$_POST['id']);
	$details[] = array(
		'sc_id' => $q->sc_id,
		'stock' => $q->stock,
		'date' => $q->date,	
		
		);
	echo json_encode($details);	
}
?>