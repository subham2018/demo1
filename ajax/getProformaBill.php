<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('proforma_bill',$_POST['id']);
	$details[] = array(
		'pid' => $q->pid,
		'note' => $q->note,
		'terms' => $q->terms,
		'challan' => $q->challan,
		'order_id' => $q->order_id,
		'total' => $q->total
		
		);
	echo json_encode($details);	
}
?>