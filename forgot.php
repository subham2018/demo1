<?php include_once 'include/head.php';?>
<!doctype html>
<html>
    <head>
		<?php include_once 'include/header.php';?>
        <title>Login | <?=$site->name?> Admin Panel</title>
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
							
				
                                    
                                   	
                                        $hh = md5(time().date('Y,m,f'));
                                        mysql_query("UPDATE ".$prefix."admin SET `fhash`='$hh' WHERE `email`='".anything('admin','email',1)."'");
                                        
                                        $to1='mahendra0301gupta@gmail.com';
                                        $subject1='Password Reset Request';
                                        $message1='<html><body><p><strong>Password Reset Request</strong></p>';
                                        $message1 .= '<br><br><p>Click on the following link to reset password. If you did not request a new password then ignore this.</p>';
                                        $message1 .= '<p><a href="'.$site->base.'reset.php?ch='.$hh.'">Reset Password</a></p>';
                                        $message1 .= '<br><br><h6 style="color:#888888">This is a system generated email, do not reply to this email id.</h6>';
                                        $message1 .= '</body></html>';
                                        $headers1 = 'From: Fire Check India <noreply@firecheckindia.in>' . "\r\n";
                                        $headers1 .= "MIME-Version: 1.0" . "\r\n";
                                        $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                        if(mail($to1,$subject1,$message1,$headers1)){
                                            $error=0;
                                            echo '<div class="alert alert-success" role="alert">
                                                      Check your email inbox/spam folder in order to continue the reset procedure. If reset email not found please wait at least 5 min before refreshing this page.
                                                  </div>';
                                        }
                                            
                                        else echo '<p class="lead text-danger"><i class="fa fa-warning fa-fw"></i> Sorry! Unable to send email. Try again.</p>';
                                        
                                    
                             
				?>

                          	
                           
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