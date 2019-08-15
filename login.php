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
                          	

                          	<form action="otp1.php" method="post">
                            	<div class="form-group">
                                	<div class="input-group">
                                    	<input class="form-control" type="email" name="aemail" placeholder="Enter email" required>
                                        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<div class="input-group">
                                    	<input class="form-control" type="text" name="aphone" placeholder="Enter phone no" required>
                                        <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<input class="btn btn-primary btn-block" type="submit" value="Request For OTP!">
                                    
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