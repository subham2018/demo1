<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('subcategory',$_POST['id']);
	$details[] = array(
		'name' => $q->name,
		'dsc' => $q->dsc,
		'price' => $q->price,
		'igst' => $q->igst,
		'sgst' => $q->sgst,
		'cgst' => $q->cgst
		
		);
	echo json_encode($details);	
}
?>