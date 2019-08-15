<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('purcheser_invoice',$_POST['id']);
	$details[] = array(
		'item_id' => $q->item_id,
		'stock' => $q->qty,
		'igst'=> $q->igst,
		'cgst'=> $q->cgst,
		'sgst'=> $q->sgst,
		'price'=> $q->bprice

		);
	echo json_encode($details);	
}
?>