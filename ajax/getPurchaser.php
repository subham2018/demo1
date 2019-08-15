<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('purchase',$_POST['id']);
	$details[] = array(
		'name' => $q->name,
		'email' => $q->email,
		'phone' => $q->phone,
		'mobile' => $q->mobile,
		'amobile' => $q->amobile,
		'business' => $q->business,
		'address' => $q->address,
		'gstin' => $q->gstin,
		'vat' => $q->vat,
		'cst' => $q->cst,
		'location' => $q->location,
		'pan' => $q->pan
		);
	echo json_encode($details);	
}
?>