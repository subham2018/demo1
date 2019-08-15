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
            	<p class="page-title"><i class="fa fa-cubes fa-fw"></i> Stock Manage</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>                    
                    <li class="active">Stock Manage</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                if(isset($_POST['submit'])){
                     //prevent mysql injection              
                    //check and insert banner
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'submit')if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma
                    
                    if(mysql_query("INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$_POST['item_id']."',`stock`='".$_POST['stock']."',`type`='infused',`ref_id`='0'")){
                        

                        $stock = intval(anything('subcategory','stock',$_POST['item_id'])) + intval($_POST['stock']);
                        mysql_query("UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);
                       echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Added successfully.</p>';
                    }
                    
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while uploading image!</p>'.mysql_error(); 
                    }
                    
                }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Stock Manage List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-default pull-right"><a href="selling_invoice.php"> Add Purchased Stock</a></button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Stock</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysql_query("SELECT * FROM ".$prefix."subcategory WHERE `del`='0'");
                                while($row=mysql_fetch_object($select)){
                                ?>
                                <tr class="<?=$row->stock < 5 ?'bg-danger':''?>">
                                    <td><?=$row->name?></td>
                                    <td><?=$row->stock?></td>
                                    <td><?=$row->datetime?></td>
                                    <td>  
                                        <a class="btn btn-default btn-sm" title="Stock Report" href="stock_report.php?id=<?=$row->id?>"><i class="fa fa-file-text-o"></i></a>

                                        <button class="btn btn-default btn-sm" title="Add stock" onClick="getItemInvoice(<?=$row->id?>)"><i class="fa fa-plus fa-fw"></i></button> 
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
                            <h4 class="modal-title">Add Stock</h4>
                        </div>
                        <div class="modal-body clearfix">                           
                                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="item_id" name="item_id" required>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Stock Quantity</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="stock" id="stock" min="0" placeholder="Enter Stock Quantity" required>
                                        </div>
                                    </div>              
                                    <hr>
                                    <input type="submit" name="submit" class="btn btn-success pull-right" value="Save">
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
    {"searchable": false,"orderable": false,"targets": 3} ],
    "order": [[ 0, 'asc' ]]
});

function getItemInvoice(id){
    $('#item_id').val(id);
    
    $('.modal').modal('show');
}
</script>
</body>
</html>