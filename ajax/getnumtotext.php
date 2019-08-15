<?php
include_once("../config/config.php");	// connecting to the database
include_once("../function/function.php");	// function
if(isset($_POST['num'])){
	check_all_var();
	echo numtowords(number_format($_POST['num'],2,'.',''));
}
?>