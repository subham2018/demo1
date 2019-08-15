<?php include_once 'include/head.php';
error_reporting(0);



?>

<!doctype html>
<html>
    <head>
        <script type="text/javascript" >
   function preventBack(){window.history.forward();}
    setTimeout("preventBack()", 0);
    window.onunload=function(){null};
</script>
		<?php include_once 'include/header.php';?>
        <title>Login | <?=$site->name?> Admin Panel</title>
        <link rel="stylesheet" type="text/css" href="assets/css/login.css">
        <style type="text/css">
            .error_message {
    color: #b12d2d;
    background: #ffb5b5;
    border: #c76969 1px solid;
}
.message {
    width: 100%;
    max-width: 300px;
    padding: 10px 30px;
    border-radius: 4px;
    margin-bottom: 5px;    
}
        </style>

    </head>


<?php 

if(!empty($_POST["submit_otp"])) {
                            $result = mysqli_query($link,"SELECT * FROM ".$prefix."admin WHERE otp='" . $_POST["otp"] . "'");
                            $count  = mysqli_num_rows($result);

                            
                            if(!empty($count)) {
                                $sql="SELECT * FROM ".$prefix."admin WHERE otp='".$_POST['otp']."'";
                                $result1=mysqli_query($link,$sql);
                                $row=mysqli_fetch_object($result);
                                session_start();
                                 $_SESSION['admin_id']=$row->id;
                                    $_SESSION['emp_type'] = 'Admin';
                                    $_SESSION['login_string']=hash('sha512', $row->password . $_SERVER['HTTP_USER_AGENT']);

                                header("location: index.php"); 
                                $result12 = mysqli_query($link,"UPDATE ".$prefix."admin SET otp = '0' WHERE otp = '" . $_POST["otp"] . "'");
                                

                            } else {
                                $success =1;
                                $error_message = "Invalid OTP!";
                            }   
                        }       
                





                          

if($_REQUEST['aemail']!="" && $_REQUEST['aphone']!=""){
                                
                                
                                $sql="SELECT * FROM ".$prefix."admin WHERE email='".$_REQUEST['aemail']."' AND phone='".$_REQUEST['aphone']."'";
                                
                                $result=mysqli_query($link,$sql);
                                
                                $c=mysqli_num_rows($result);
                               
                                $row=mysqli_fetch_object($result);
                                
                                
                                    $otp = rand(100000,999999);

                                
                                if ($c != 1 && $row->status != '1' && $row->phone!=$_REQUEST['aphone'])
                                {

                                   header("location: login.php");
                                }
                                else {
                                    $result = mysqli_query($link,"UPDATE ".$prefix."admin SET otp = '" . $otp . "'  WHERE email='".$_REQUEST['aemail']."'");
  



//SMS API INTEGRATION DONE                               
                            
$msg1="Dear Admin, Your OTP is ".$otp.". Please do not share your OTP with anyone";
    //$date=date("d/m/Y");

    $url = "https://bulksms.soinit-ts.com/api/api_http.php";
    // $recipients = '';     
    $param = array(
        'username' => 'firecheck',
        'password' => 'Soinitapi@2019',
        'senderid' => 'FIRECH',
        'text' => $msg1,
        'route' => 'Informative',
        'type' => 'text',
        //'to' => implode(';', $recipients)
        'to' => $_REQUEST['aphone']
    );
    $post1 = http_build_query($param, '', '&');
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $url);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $post1);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection"));
    $resultsuccess1 = curl_exec($ch1);
    if(curl_errno($ch1)) {
        $resulterror1 = "cURL ERROR: " . curl_errno($ch1) . " " . curl_error($ch1);
    } else {
        $returnCode1 = (int)curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        switch($returnCode1) {
            case 200 :
                break;
            default :
                $resulterror1 = "HTTP ERROR: " . $returnCode1;
        }
    }
    curl_close($ch1);








} // else sesh hoyeche

} // parent loop sesh hoyeche

                        ?>



    <body>
        <div class="container">
        	<div class="login-cont">
            	<div class="row">
                	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    	
                    	<div class="panel panel-default">
                          <div class="panel-body">
                            <img src="<?=UPLOAD_IMG_PATH.$site->logo?>">
                            <h3 style="margin: 0;margin-bottom: 17px; color: #000080; font-size:32px" >FIRE CHECK (INDIA)</h3>
                             <h4 style="margin: 0;margin-bottom: 5px; font-size: 14px; color: #cc0000">A HOUSE OF TOTAL FIRE PROTECTION</h4><br>

							<!-- <center><?=@$error_message;?></center> -->

                          	<form action="" method="post">

                            	<div class="form-group">
                                	<div class="input-group">
                                        <input class="form-control" type="hidden" name="aemail" value="<?=$_REQUEST['aemail']?>">
                                    	<input class="form-control" type="text" name="otp" placeholder="Enter 6 Digits OTP Code" required>
                                        <span class="input-group-addon"><i class="fa fa-commenting-o fa-fw"></i></span>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                	<input type="submit" class="btn btn-primary btn-block" name="submit_otp" value="VERIFY OTP AND LOGIN" class="btnSubmit"><br>
                                    <a href="login.php" class="btn btn-danger btn-block">BACK TO LOGIN</a>
                                </div>  
                                                             
                            </form>
                           
                           
                          </div>
                        </div>
                        
                        <h6 style>&copy; <?=date('Y')?> &middot; <?=$site->name?>. All Rights Reserved.<br><br>Designed & Developed by <br><a href="http://soinit-ts.com" target="new" style="color: #fff; text-decoration: none">Soinit Technology Solutions PVT. LTD.</a></h6>
                    </div>
                </div>
            </div>
        </div>

    <?php include_once 'include/footer.php';?>

    </body>
</html>