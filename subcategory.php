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
            	<p class="page-title"><i class="fa fa-list-alt"></i> Product</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="category.php">Category</a></li>
                    <li class="active">Product</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <p class="well well-sm"><b>Category:</b> <?=anything('category','name',$_GET['id'])?></p>
                            <?php
                //Projects Save
                if(isset($_POST['submit'])){
                     //prevent mysql injection              
                    //check and insert banner
                           
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'submit')if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma
                    
                    if(mysqli_query($link,"INSERT INTO ".$prefix."subcategory SET ".$query))
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Product Added successfully.</p>';
                    
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

                    if(mysqli_query($link,"UPDATE ".$prefix."subcategory SET ".$query." WHERE `id`=".$_POST['pid'])) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Product Updated successfully.</p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while updating details!</p>';   
                    }
                }

                if(isset($_GET['del'])){
                    //delete Projects
                     $img=anything('category','image',$_GET['del']);
                    if(mysqli_query($link,"UPDATE ".$prefix."subcategory SET `del`='1' WHERE id=".variable_check($_GET['del']))){ //delete info
                       
                        
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Product Deleted successfully.</p>';

                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
                ?>
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Product</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="cat_id" value="<?=$_GET['id']?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="name" maxlength="" placeholder="Enter Subcategory name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Product Unit</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="unit" maxlength="" placeholder="Enter unit" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="dsc"  placeholder="Enter Description" required></textarea>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Base Price</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="price" maxlength="" placeholder="Enter Base Price" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">IGST (%)</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" max="100" class="form-control" name="igst"  placeholder="Enter IGST Percentage" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CGST (%)</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" max="100" class="form-control" name="cgst"  placeholder="Enter CGST Percentage" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">SGST (%)</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" max="100" class="form-control" name="sgst"  placeholder="Enter SGST Percentage" required>
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Product List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Record</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Base Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."subcategory where `del`='0' AND `cat_id` ='".$_GET['id']."'");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr class="<?=$row->stock < 5 ?'bg-danger':''?>">
                                    <td><?=$row->name?></td>
                                    <td><?=$row->price?></td>
                                    <td><?=$row->stock?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getSubcategory(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button>
                                                                             
                                       
                                        <a class="btn btn-danger btn-sm" href="?id=<?=$_GET['id']?>&del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <small><i class="fa fa-info-circle fa-fw"></i> Red marked products are low / out of stock.</small>
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
                                    <label class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="name" maxlength="" placeholder="Enter Category Name" required>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="dsc" id="dsc" placeholder="Enter Description" required></textarea>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Base Price</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" name="price" id="price" maxlength="" placeholder="Enter Base Price" required>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">IGST (%)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="igst" id="igst" min="0" max="100" placeholder="Enter IGST Percentage" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">CGST (%)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="cgst" id="cgst" min="0" max="100" placeholder="Enter CGST Percentage" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">SGST (%)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="sgst" id="sgst" min="0" max="100" placeholder="Enter SGST Percentage" required>
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
    {"searchable": false,"orderable": false,"targets": 2} ],
    "order": [[ 0, 'asc' ]]
});
function getSubcategory(id){
    $.ajax({
        type: 'post',
        url:'ajax/getSubcategory.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#name').val(data[0]['name']);
            $('#dsc').val(data[0]['dsc']);
            $('#price').val(data[0]['price']);
            $('#igst').val(data[0]['igst']);
            $('#cgst').val(data[0]['cgst']);
            $('#sgst').val(data[0]['sgst']);
            // $('#stock').val(data[0]['stock']);
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