<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('item_invoice',$_POST['id']);
	$details[] = array(
		'item_id' => $q->item_id,
		'stock' => $q->stock,
		'igst'=> $q->igst,
		'cgst'=> $q->cgst,
		'sgst'=> $q->sgst,
		'price'=> $q->price

		);
	echo json_encode($details);	
}
?>