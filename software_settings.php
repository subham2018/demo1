<?php include_once 'include/head.php';?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">

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
            	<p class="page-title"><i class="fa fa-cogs fa-fw"></i> Software Settings</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>                    
                    <li class="active">Software Settings</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                if(isset($_POST['dbbackup'])){
                    if(md5($_POST['pass'])=='a7f7c646a3cdcb4b6bd3076bccf4511d'){
                        $_SESSION['token'] = md5(time());
                        $_SESSION['empty'] = false;
                        echo '<p class="alert alert-info"><i class="fa fa-check fa-fw"></i> Backup token generated. Do not share this link. <a class="btn btn-xs btn-default" href="dbbackup.php?token='.$_SESSION['token'].'" target="new">Download Backup</a></p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! Wrong password.</p>';
                }
                if(isset($_POST['dbclean'])){
                    if(md5($_POST['pass'])=='a7f7c646a3cdcb4b6bd3076bccf4511d'){
                        if(isset($_POST['bk'])){
                            $_SESSION['empty'] = true;
                            $_SESSION['token'] = md5(time());
                            echo '<p class="alert alert-info"><i class="fa fa-check fa-fw"></i> Download token generated. Click on <strong>Downlaod Backup</strong> to download and clean database. Do not share this link. <a class="btn btn-xs btn-default" href="dbbackup.php?token='.$_SESSION['token'].'" target="new">Download Backup</a></p>';
                        }
                        else{
                            $mysqli = new mysqli("localhost", "wwwsoini_billing", '@WE#$RT%', "wwwsoini_billing");
                            $mysqli->query('SET foreign_key_checks = 0');
                            if ($result = $mysqli->query("SHOW TABLES"))
                            {
                                $a = array('soinit_admin','soinit_setting','soinit_software_setting');
                                while($row = $result->fetch_array(MYSQLI_NUM))
                                {   
                                    if(!in_array($row[0], $a))
                                    $mysqli->query('TRUNCATE `'.$row[0].'`');
                                }
                            }

                            $mysqli->query('SET foreign_key_checks = 1');
                            $mysqli->close();
                            echo '<p class="alert alert-info"><i class="fa fa-check fa-fw"></i> Database cleaned.</p>';
                        }
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! Wrong password.</p>';
                }

				if(isset($_POST['submit'])){
				 //prevent mysql injection
					//generate query from post
					$query='';
					foreach($_POST as $key=>$value) if($key != 'submit')$query .="`$key`='$value',";
					$query = substr($query, 0, -1);//omit last comma
					
					if(mysqli_query($link,"UPDATE ".$prefix."software_setting SET ".$query." WHERE id=1")){ //insert all text info
						
						//check and insert logo
						if(!empty($_FILES['logo']['tmp_name']) && is_uploaded_file( $_FILES['logo']['tmp_name'] )){
							
							//conver file name to md5 and prepend timestamp
							$fbasename=time().'_'.md5($_FILES["logo"]["name"]).'.'.pathinfo($_FILES["logo"]["name"],PATHINFO_EXTENSION);
							$target_file = UPLOAD_IMG_PATH . $fbasename;
							$image_temp = $_FILES['logo']['tmp_name'];
							$image_size_info    = getimagesize($image_temp);
							if($image_size_info){
								$image_width        = $image_size_info[0];
								$image_height       = $image_size_info[1];
								$image_type         = $image_size_info['mime']; 
							}else{
							   exit;
							}
							//check if image is in png format or not
							switch($image_type){
								case 'image/png': $image_res = imagecreatefrompng($image_temp);break;
								default: $image_res = false;
							}
							//resize to max height 60px and save
							if($image_res != false && resize_image($image_res, $target_file, $image_type, 100, $image_width, $image_height, 80, true, false)){
								@unlink(UPLOAD_IMG_PATH . $site->logo);
								mysqli_query($link,"UPDATE ".$prefix."software_setting SET `logo`='$fbasename' WHERE id=1");
								$error=isset($error) && $error == 1? 1:0;
							}
							else $error=1;	
						}
						
						//check if error occure in any step
						if(isset($error) && $error == 0)
							echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Image and Details updated successfully.</p>';
						elseif(isset($error) && $error == 1)
							echo '<p class="alert alert-warning<i class="fa fa-check fa-fw"></i> Details updated successfully. But there is an error while uploading image!</p>';
						else echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Details updated successfully.</p>';

                        //financial year
					}
					else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! Something went wrong.'.mysqli_error().'</p>';
					$site = site_info();
				}
                if(isset($_POST['fy'])){
                    //echo "abcd"; 
                    $last_date=mysqli_fetch_object(mysqli_query($link,"SELECT * FROM ".$prefix."financial_year ORDER BY `id` DESC LIMIT 1"));
                    if(strtotime($last_date->tdate) < time()){

                        if(mysqli_query($link,"INSERT INTO ".$prefix."financial_year SET `fdate`='".$_POST['fdate']."',`tdate`='".$_POST['tdate']."'")){

                             echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Financial year added successfully.</p>'.mysqli_error();
                        }else{
                            echo '<p class="alert alert-danger"><i class="fa fa-close fa-fw"></i> Something went worng.</p>'.mysqli_error();
                        }
                    }else echo '<p class="alert alert-danger"><i class="fa fa-close fa-fw"></i> Financial year not Ended.</p>'.mysqli_error();
                }

                $last_date=mysqli_fetch_object(mysqli_query($link,"SELECT * FROM ".$prefix."financial_year ORDER BY `id` DESC LIMIT 1"));
                ?>
                 <!--add new-->
                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Edit Software Settings</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Financial Year</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="from_date" name="fdate" value="<?=isset($last_date->fdate)?$last_date->fdate:''?>"  placeholder="Select date" >
                                        <span class="input-group-addon">to</span>
                                         <input type="text" class="form-control" id="to_date"  name="tdate" value="<?=isset($last_date->tdate)?$last_date->tdate:''?>" placeholder="Select date" >
                                         <div class="input-group-btn">
                                            <button class="btn btn-primary" name="fy" type="submit" >Save</button>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            
                        </form>
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                   <input type="text" class="form-control" name="name" value="<?=anything('software_setting','name',1)?>" placeholder="Enter Software Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Logo</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="<?=$site->base?>/admin/<?=UPLOAD_IMG_PATH . anything('software_setting','logo',1)?>" height="18px"></span>
                                        <input type="text" class="form-control" readonly placeholder="Choose PNG file">
                                        <input type="file" name="logo" onChange="updateName(this)" accept="image/x-png" class="hidden">
                                        <span class="input-group-btn"><button data-file-input='logo' type="button" class="btn btn-primary">Browse</button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                   <textarea class="form-control" name="address" placeholder="Enter Address"><?=anything('software_setting','address',1)?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-10">
                                   <input type="tel" class="form-control" name="phone" placeholder="Enter Phone Number" value="<?=anything('software_setting','phone',1)?>">
                                </div>
                            </div>             
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                   <input type="text" class="form-control" name="email" placeholder="Enter Email Id" value="<?=anything('software_setting','email',1)?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fax</label>
                                <div class="col-sm-10">
                                   <input type="text" class="form-control" name="fax" placeholder="Enter Fax Number" value="<?=anything('software_setting','fax',1)?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Website</label>
                                <div class="col-sm-10">
                                   <input type="url" class="form-control" name="website" value="<?=anything('software_setting','website',1)?>" placeholder="Enter website">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Invoice term</label>
                                <div class="col-sm-10">
                                   <textarea class="form-control" name="invoice_term" id="term" placeholder="Write Invoice Term"><?=htmlspecialchars_decode(anything('software_setting','invoice_term',1))?></textarea>
                                </div>
                            </div>                           
                            

                            <div class="form-group">
                                <label class="col-sm-2 control-label">GST IN*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="gst" placeholder="Enter  GST IN "  value="<?=anything('software_setting','gst',1)?>">
                                </div>
                            </div> 
                        
                        
                           <!-- <div class="form-group">
                                <label class="col-sm-2 control-label">VAT NO</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="vat" placeholder="Enter  VAT no "  value="<?=anything('software_setting','vat',1)?>">
                                </div>
                            </div> 
                        
                        
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CST NO</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="cst"  placeholder="Enter  CST no "  value="<?=anything('software_setting','cst',1)?>">
                                </div>
                            </div> -->
                        
                        
                            <div class="form-group">
                                <label class="col-sm-2 control-label">PAN NO</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pan" placeholder="Enter  PAN no "  value="<?=anything('software_setting','pan',1)?>">
                                </div>
                            </div>
                            <p class="label-hr">Bank Account Details</p>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Account Details</label>
                                <div class="col-sm-10">
                                   <textarea type="text" class="form-control" name="bank_account" id="ad" placeholder="Enter Account details"><?=htmlspecialchars_decode(anything('software_setting','bank_account',1))?></textarea>
                                </div>
                            </div>
                            <hr>
                            <input type="button" class="btn btn-danger pull-right" value="Empty Database" data-toggle="modal" data-target="#empty">
                            <input type="button" class="btn btn-primary pull-right" value="Backup Database" data-toggle="modal" data-target="#backup">
                            <input type="submit" name="submit" class="btn btn-success pull-right" value="Save">
                        </form>
                    </div>
                </div>

                <div class="modal modal-fade" id="backup">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Backup Database</h4>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label>Enter High-Secutiry Password:</label>
                                        <input type="password" name="pass" placeholder="********" required class="form-control">
                                        <small><i class="fa fa-info-circle fa-fw"></i> Ask software manufacturer for password.</small>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="dbbackup" value="Download" class="btn btn-success">
                                        <input type="button" data-dismiss="modal" value="Close" class="btn btn-default">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal modal-fade" id="empty">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Empty Database</h4>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label>Enter High-Secutiry Password:</label>
                                        <input type="password" name="pass" placeholder="********" required class="form-control">
                                        <small><i class="fa fa-info-circle fa-fw"></i> Ask software manufacturer for password.</small>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                          <input type="checkbox" value="1" name="bk"> Download Current Database
                                        </label>
                                      </div>
                                    <div class="form-group">
                                        <input type="submit" name="dbclean" value="Clean" class="btn btn-success">
                                        <input type="button" data-dismiss="modal" value="Close" class="btn btn-default">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/ckeditor/ckeditor.js"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">
CKEDITOR.replace( 'term');
CKEDITOR.replace( 'ad');
$('#from_date').datepicker({
    format:"yyyy-mm-dd",
    //minViewMode:1
});
$('#to_date').datepicker({
    format:"yyyy-mm-dd",
    //minViewMode:1
});
$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 6} ],
    "order": [[ 0, 'asc' ]]
});
function getStockManage(id){
    $.ajax({
        type: 'post',
        url:'ajax/getStockManage.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#name').val(data[0]['name']);
            $('#eximg').attr('src',"<?=UPLOAD_IMG_PATH?>" + data[0]['img']);
            $('#address').val(data[0]['address']);
            $('#phone').val(data[0]['phone']);
            $('#fax').val(data[0]['fax']);
            $('#url').val(data[0]['url']);
            $('#email').val(data[0]['email']);
            $('#term').val(data[0]['term']);
            
            $('.modal').modal('show');
        }
    })
}
</script>
<?php if(isset($_GET['del'])){?>
<div id="url_replace">
    <script type="text/javascript">
        $(document).ready(function(e) {
            window.history.replaceState(null, null, '<?=$page?>');
            $('#url_replace').remove();
        });
    </script>
</div>
<?php }?>
</body>
</html>