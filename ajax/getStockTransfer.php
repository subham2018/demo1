<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('stock_transfer',$_POST['id']);
	$details[] = array(
		'w_id' => $q->w_id,
		'item_id' => $q->item_id,
		'stock'=> $q->stock
		);
	echo json_encode($details);	
}
?>