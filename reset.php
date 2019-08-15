<?php include_once 'include/head.php';
if(isset($_GET['ch'])){
    $select = mysql_query("SELECT * FROM `".$prefix."admin` WHERE `fhash`='".$_GET['ch']."'");
    if(mysql_num_rows($select) == 1){
        
    }
    elseif(!isset($_GET['exp']))if(!isset($_GET['cp'])) header("location:reset.php?exp=1");
}elseif(!isset($_GET['exp']))if(!isset($_GET['cp']))  header("location:reset.php?exp=1");

if(isset($_POST['submit'])){
    if($_POST['password'] == $_POST['cpassword']){
        if(mysql_query("UPDATE ".$prefix."admin SET `fhash`='', `password`='".md5($_POST['password'])."' WHERE  fhash='".$_POST['hash']."'")){
            header("location:reset.php?cp=1");
        }
    }
    else header("location:reset.php?cp=0&ch=".$_POST['hash']);
}
?>
<!doctype html>
<html>
    <head>
		<?php include_once 'include/header.php';?>
        <title>Reset Password| <?=$site->name?> Admin Panel</title>
        <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    </head>

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
	                              <?php
	                            
	                            if(isset($_GET['ch'])){
	                                if(isset($_GET['cp']) && $_GET['cp']=='0')
	                                    echo '<p class="lead text-center text-danger"><i class="fa fa-lock fa-lg fa-fw"></i>Password Mismatch.</p>';
	                            
	                            ?>

                          	<form role="form" class="intro-form" action="" method="post">
                                <input type="hidden" name="hash" value="<?=$_GET['ch']?>" required>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="New Password" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="cpassword" placeholder="Confirm New Password" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">RESET</button>
                                </div>

                                <div class="form-group text-center">                                   
                                    <a href="login.php">Login</a>
                                </div>
                            </form>
                            <?php }elseif(isset($_GET['cp']) && $_GET['cp']=='1') {?>
                                <p class="lead text-center"><i class="fa fa-check fa-lg fa-fw"></i><br><br><b>Password Changed.</b><br><br><a class="btn btn-custom btn-sm" href="./">LOGIN</a></p>
                            <?php }else{?>
                                <p class="lead text-center"><i class="fa fa-unlink fa-lg fa-fw"></i><br><br><b>Sorry! Link expired.</b><br><br></p>
                                <div class="form-group text-center">                                   
                                    <a href="login.php">LOGIN</a>
                                </div>
                            <?php }?>
                           
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