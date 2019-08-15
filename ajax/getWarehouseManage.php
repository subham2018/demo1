<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('warehouse_manage',$_POST['id']);
	$details[] = array(
		'name' => $q->name,
		'email' => $q->email,
		'phone' => $q->phone,
		'address' => $q->address,
		'fax' => $q->fax,
		'password' => $q->password,
		'location' => $q->location,
		'status' => $q->status
		
		);
	echo json_encode($details);	
}
?>