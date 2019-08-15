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
            	<p class="page-title"><i class="fa fa-list-alt"></i> Warehouse Manage</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Warehouse manage</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                            <?php
                //Projects Save
                if(isset($_POST['submit'])){
                     //prevent mysql injection              
                    //check and insert banner
                    $_POST['password']=md5($_POST['password']);
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'submit')if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma
                    
                    if(mysqli_query($link,"INSERT INTO ".$prefix."warehouse_manage SET ".$query))
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>  Added successfully.</p>';
                    
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while uploading image!</p>'; 
                    }
                    
                }

                //Project edit
                if(isset($_POST['usubmit'])){
                     //prevent mysql injection              
                    $_POST['password']=$_POST['password']!=''?md5($_POST['password']):'';
                    if($_POST['password']=='') unset($_POST['password']);
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'usubmit')if($key != "pid")if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    if(mysqli_query($link,"UPDATE ".$prefix."warehouse_manage SET ".$query." WHERE `id`=".$_POST['pid'])) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Updated successfully.</p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while updating details!</p>';   
                    }
                }

                if(isset($_GET['del'])){
                    //delete Projects
                     $img=anything('category','image',$_GET['del']);
                    if(mysqli_query($link,"DELETE FROM ".$prefix."warehouse_manage WHERE id=".variable_check($_GET['del']))){ //delete info
                       
                        
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Deleted successfully.</p>';

                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
                ?>
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                        
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Warehouse Name</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="name" maxlength="" placeholder="Enter Manager Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address"  placeholder="Enter Address" required></textarea>
                                </div>
                            </div>               
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="location"  id="loc" placeholder="Enter Seller location" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="tel"  class="form-control" name="phone" maxlength="" placeholder="Enter Phone No" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" maxlength="256" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fax (Optional)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fax" maxlength="256" placeholder="Enter Fax" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password" maxlength="" placeholder="Enter Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-control" required name="status">
                                        <option selected disabled value="0">Deactive</option>
                                        <option  value="1">Active</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <hr>
                            <input type="reset" class="btn btn-danger pull-right" value="Cancel" onClick="$('#addnew').hide('slow')">
                            <input type="submit" name="submit" class="btn btn-success pull-right" value="Save">
                        </form>
                    </div>
                </div>
                <!--list-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i>Warehouse Manage List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Record</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Warehouse Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_manage");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->name?></td>
                                    <td><?=$row->phone?></td>
                                    <td><?=$row->email?></td>
                                    <td><?=$row->location?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getWarehouseManage(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button>                                       
                                       
                                        <a class="btn btn-danger btn-sm" href="?del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog">
                  <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">View / Edit Warehose Manage</h4>
                        </div>
                        <div class="modal-body clearfix">                           
                          
                            <div class="col-sm-12">
                                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="pid" name="pid" required>
                                    <div class="form-group">
                                <label class="col-sm-3 control-label">Warehouse Name</label>
                                <div class="col-sm-9">
                                    <input type="text"  class="form-control" name="name" id="name" maxlength="" placeholder="Enter Manager Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="address" id="address"  placeholder="Enter Address" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Location</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="location"  id="loc1" placeholder="Enter Seller location" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-3 control-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="tel"  class="form-control" name="phone" id="phone" maxlength="" placeholder="Enter Phone No" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="email" maxlength="256" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fax (Optional)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="fax" id="fax" maxlength="256" placeholder="Enter Fax">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" id="password" maxlength="" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-9">
                                    <select class="form-control" required name="status" id="status">
                                        <option value="0">Deactive</option>
                                        <option  value="1">Active</option>
                                        
                                    </select>
                                </div>
                            </div>
                                    
                                    <hr>
                                    <input type="submit" name="usubmit" class="btn btn-success pull-right" value="Save">
                                </form>
                            </div>                            
                        </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                
                
            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyCm2TyXncpU8er_lypzGpwrOOlxz_I_g3M"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">

    var autocomplete3 = new google.maps.places.Autocomplete(document.getElementById('loc'), {types: ['(cities)']}); 
    var autocomplete4 = new google.maps.places.Autocomplete(document.getElementById('loc1'), {types: ['(cities)']}); 
</script>
<script type="text/javascript">

$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 4} ],
    "order": [[ 0, 'asc' ]]
});
function getWarehouseManage(id){
    $.ajax({
        type: 'post',
        url:'ajax/getWarehouseManage.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#name').val(data[0]['name']);
            $('#email').val(data[0]['email']);
            $('#phone').val(data[0]['phone']);
            $('#address').val(data[0]['address']);
             $('#loc1').val(data[0]['location']);
            $('#fax').val(data[0]['fax']);
            $('#status').val(data[0]['status']);
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