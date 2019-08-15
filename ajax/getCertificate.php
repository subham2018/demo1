<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['id'])){
	check_all_var();
	$q = row('certificate',$_POST['id']);
	$details[] = array(
		'name' => $q->name,
		'compid' => $q->compid,
		'address1' => $q->address1,
		'address2' => $q->address2,
		'address3' => $q->address3,
		'odate' => $q->odate,
		'pdate' => $q->pdate,
		'cdate' => $q->cdate,
		'date' => $q->date,
		'type' => $q->type,
		'dsc' => htmlspecialchars_decode($q->dsc)
		
		);
	echo json_encode($details);	
}
?>