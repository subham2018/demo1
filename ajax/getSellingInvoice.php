<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('seller_invoice',$_POST['id']);
	$details[] = array(
		'seller_id' => $q->seller_id,
		'in_number' => $q->in_number,
		'hsn'=> $q->hsn,
		'sac'=> $q->sac,
		'gst'=> $q->gst,
		'amount'=> $q->amount,
		'date'=> $q->date

		);
	echo json_encode($details);	
}
?>