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
            	<p class="page-title"><i class="fa fa-sitemap fa-fw"></i> Product Category</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Product Category</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                //Projects Save
                if(isset($_POST['submit'])){
                     //prevent mysql injection              
                    //check and insert banner
                           
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'submit')if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma
                    
                    if(mysqli_query($link,"INSERT INTO ".$prefix."category SET ".$query))
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Category Added successfully.</p>';
                    
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while uploading image!</p>'; 
                    }
                    
                }

                //Project edit
                if(isset($_POST['usubmit'])){
                     //prevent mysql injection              
                    
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'usubmit')if($key != "pid")if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    if(mysqli_query($link,"UPDATE ".$prefix."category SET ".$query." WHERE `id`=".$_POST['pid'])) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Category Updated successfully.</p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while updating details!</p>';   
                    }
                }

                if(isset($_GET['del'])){
                    //delete Projects
                     $img=anything('category','image',$_GET['del']);
                    if(mysqli_query($link,"DELETE FROM ".$prefix."category WHERE id=".variable_check($_GET['del']))){ //delete info
                       
                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `del`='1' WHERE `cat_id`='".$_GET['del']."'");
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Category Deleted successfully.</p>';

                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
                ?>
                
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Product Category</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" maxlength="256" placeholder="Enter category name" required>
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Category List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Record</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."category");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->name?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getCategory(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button>
                                        <a class="btn btn-primary btn-sm" title="Product" href="subcategory.php?id=<?=$row->id?>"><i class="fa fa-sitemap fa-fw"></i></a>
                                       
                                        <a class="btn btn-danger btn-sm" href="category.php?del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
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
                            <h4 class="modal-title">View / Edit Product Category</h4>
                        </div>
                        <div class="modal-body clearfix">                          
                               
                            <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="pid" name="pid" required>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="name" maxlength="256" placeholder="Enter category title" required>
                                    </div>
                                </div>
                                
                                <hr>
                                <input type="submit" name="usubmit" class="btn btn-success pull-right" value="Save">
                            </form>                               
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
<script src="assets/js/common.js"></script>
<script type="text/javascript">

$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 1} ],
    "order": [[ 0, 'desc' ]]
});
function getCategory(id){
    $.ajax({
        type: 'post',
        url:'ajax/getCategory.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#title').val(data[0]['title']);
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