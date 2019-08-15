<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('subcategory',$_POST['id']);
	$details[] = array(
		'stock'=> $q->stock,
		'ig'=> $q->igst,
		'cg'=> $q->cgst,
		'sg'=> $q->sgst,
		'unit'=> $q->unit,
		'bp'=> $q->price

		);
	echo json_encode($details);	
}
?>