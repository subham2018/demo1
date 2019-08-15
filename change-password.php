<?php include_once 'include/head.php';?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<style type="text/css">
    .tab-pane{
        padding:10px;
        border:thin solid #ddd;
        border-top:none;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
</head>

<body>
<?php include_once 'include/navbar.php';?>  
		
		
         <div class="content">
            <div class="overlay"></div><!--for mobile view sidebar closing-->
            <!--title and breadcrumb-->
            <div class="top">
                <p class="page-title"><i class="fa fa-info-circle fa-fw"></i> Profile</p>
                <ol class="breadcrumb">
                    <!-- <li><a href="index.php">Home</a></li> -->
                    <li><a href="index.php">Admin panel</a></li>
                    
                    <li class="active">Profile</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                if($user_type=='WH'){
                    $db='warehouse_manage';
                     $q=" WHERE `id`='".$user->id."'";

                }if($user_type=='Admin'){
                    $db='admin';
                    $q=" WHERE `id`='1'";
                }
                if(isset($_POST['psubmit'])){
                    if($_POST['p']==$_POST['rep']){
                        if(mysql_query("UPDATE ".$prefix.$db." SET `password`='".md5($_POST['p'])."',`email`='".$_POST['email']."'".$q)) //insert all text info
                            header('location: logout.php');
                        else    
                            echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error!</p>';
                    }
                    else
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! Passwords mismatch!</p>';
                }
                

                ?>
                <!--add new-->
                
                <!--list-->
               
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-lock fa-fw"></i>Change Password </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                        
                            <div class="col-sm-12">
                                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">New Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="email" placeholder="Enter new email" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="p" placeholder="Enter new password" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Confirm New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="rep" placeholder="Re-Enter new password" required>
                                        </div>
                                    </div>
                                    <small><i class="fa fa-info-circle fa-fw"></i> You will be signed out.</small>
                                    <hr>
                                    <!-- <a class="btn btn-default pull-right" id="topic" href="">Manage Adons</a> -->
                                    <input type="submit" name="psubmit" class="btn btn-success pull-right" value="Save">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <!--main content end-->
        </div>
</div>

<?php include "include/footer.php";?>
<script src="assets/js/common.js"></script>
</body>
</html>
