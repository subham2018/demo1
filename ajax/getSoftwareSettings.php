<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('software_setting',$_POST['id']);
	$details[] = array(
		'name' => $q->name,
		'image' => $q->logo,
		'phone' => $q->phone,
		'email' => $q->email,
		'fax' => $q->fax,
		'address' => $q->address,
		'term' => $q->invoice_term,

		
		);
	echo json_encode($details);	
}
?>