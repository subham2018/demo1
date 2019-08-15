<?php


// display errors
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

ob_start();
@session_start();


include_once(__DIR__ ."/../config/config.php");	// connecting to the database
include_once(__DIR__ ."/../function/function.php");	// function

$site =site_info();
$now = date('Y-m-d H:i:s');
$siteurl=$site->base;

define("UPLOAD_DOC_PATH", "upload/doc/");
define("UPLOAD_IMG_PATH", "upload/original/");
define("UPLOAD_TMB_IMG_PATH", "upload/thumbnail/t_");


if(isset($_SESSION['admin_id'])) {
	
	$user_id = $_SESSION['admin_id'];
	$user_type = 'Admin';
	$login_string = $_SESSION['login_string'];
	$user_browser = $_SERVER['HTTP_USER_AGENT'];
	
	
		$user =row('admin',$user_id);
		
	
	$login_check = hash('sha512', $user->password . $user_browser);
	
	if ($login_check == $login_string) {
		$userid = $user->id;
		if(basename($_SERVER['PHP_SELF'])=="otp1.php") header("location: index.php");
	}
	else header("location: logout.php");

	//Closing Stock manage
	$s=mysqli_query($link,"SELECT * FROM ".$prefix."financial_year ORDER BY `id` DESC LIMIT 1");
	if(mysqli_num_rows($s)>0){
		$last_date=mysqli_fetch_object(mysqli_query($link,"SELECT * FROM ".$prefix."financial_year ORDER BY `id` DESC LIMIT 1"));
		if(strtotime($last_date->tdate) < time() AND $last_date->status=='0'){
			$select=mysqli_query($link,"SELECT * FROM ".$prefix."subcategory");
			while($remain_stock=mysqli_fetch_object($select)){
				$stock=$remain_stock->stock;
				mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `stock`='".$stock."',`sc_id`='".$remain_stock->id."',`type`='closing' ");
			}
			mysqli_query($link,"UPDATE ".$prefix."financial_year SET `status`='1' WHERE `id`='".$last_date->id."'");
		}
	}
}
else if((basename($_SERVER['PHP_SELF'])!="login.php") && (basename($_SERVER['PHP_SELF'])!="forgot.php") && (basename($_SERVER['PHP_SELF'])!="reset.php")   && (basename($_SERVER['PHP_SELF'])!="otp1.php")) header("location: login.php");
//Security Checking
if(isset($_GET)) $_GET=variable_check($_GET);
if(isset($_POST)) check_all_var();
?>