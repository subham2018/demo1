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
                <p class="page-title"><i class="fa fa-cubes fa-fw"></i> Invoice</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="purcheser_bill.php">Purchaser Billing</a></li>
                    <li class="active">Invoice</li>
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
                    
                    if(mysql_query("INSERT INTO ".$prefix."purcheser_invoice SET ".$query)){
                        mysql_query("INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$_POST['item_id']."',`stock`='".$_POST['qty']."',`type`='cleared',`ref_id`='".mysql_insert_id()."'");

                        $stock = intval(anything('subcategory','stock',$_POST['item_id'])) - intval($_POST['qty']);
                        mysql_query("UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);
                       echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Added successfully.</p>';
                    }
                    
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while uploading image!</p>'.mysql_error(); 
                    }
                    
                }

                //Project edit
                if(isset($_POST['usubmit'])){
                     //prevent mysql injection              
                    
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'usubmit')if($key != "pid")if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    $prevstock = intval(anything('purcheser_invoice','qty',$_POST['pid']));

                    if(mysql_query("UPDATE ".$prefix."purcheser_invoice SET ".$query." WHERE `id`=".$_POST['pid'])){
                        mysql_query("UPDATE ".$prefix."stock_manage SET `sc_id`='".$_POST['item_id']."',`stock`='".$_POST['qty']."' WHERE `ref_id`='".$_POST['pid']."'");

                        $stock = intval(anything('subcategory','stock',$_POST['item_id'])) + $prevstock - intval($_POST['qty']);
                        mysql_query("UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);
                       echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Updated successfully.</p>';
                    }         
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while updating details!</p>'.mysql_error();   
                    }
                }
                 if(isset($_GET['del'])){
                    //delete Projects
                    $prevstock = intval(anything('purcheser_invoice','qty',$_POST['pid']));
                    
                    if(mysql_query("DELETE FROM ".$prefix."purcheser_invoice WHERE id=".variable_check($_GET['del']))){ //delete info
                        mysql_query("DELETE FROM ".$prefix."stock_manage WHERE `ref_id`='".variable_check($_GET['del'])."'");
                        
                         $stock = intval(anything('subcategory','stock',$_POST['item_id'])) - $prevstock;
                         mysql_query("UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);

                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>   Deleted successfully.</p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
               
                ?>
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Invoice</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="inv_id" value="<?=$_GET['id']?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Item</label>
                                <div class="col-sm-10">
                                   <select class="form-control" required name="item_id" id="">
                                        <option selected disabled value="">Select Item</option>
                                        <?php
                                        $select=mysql_query("SELECT * FROM ".$prefix."category");
                                        while($row=mysql_fetch_object($select)){?>
                                        <optgroup  label="<?=$row->name?>">
                                        <?php
                                            $select1=mysql_query("SELECT * FROM ".$prefix."subcategory WHERE `cat_id`='".$row->id."'");
                                            while($row1=mysql_fetch_object($select1)){
                                            ?>
                                            <option value="<?=$row1->id?>"><?=$row1->name?></option>
                                            <?php }?>
                                        </optgroup>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Quantity</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="qty" id="" min="0" placeholder="Enter  Quantity" required>
                                </div>
                            </div>                    
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Base Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="bprice" id="" min="0" placeholder="Enter Amount" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">IGST</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="igst" id="" min="0"  max="100" placeholder="Enter IGST Amount" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CGST</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="cgst" id="" min="0"  max="100" placeholder="Enter CGST Amount" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">SGST</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="sgst" id="" min="0"  max="100" placeholder="Enter SGST Amount" required>
                                </div>
                            </div>                          
                            
                            <hr>
                            <input type="reset" class="btn btn-danger pull-right" value="Cancel" onClick="$('#addnew').hide('slow')">
                            <input type="submit" name="submit" class="btn btn-success pull-right" value="Save">
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Invoice List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right"> Add Item</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>QTY</th>
                                    <th>Base Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysql_query("SELECT * FROM ".$prefix."purcheser_invoice WHERE `inv_id` ='".$_GET['id']."'");
                                while($row=mysql_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=anything('subcategory','name', $row->item_id)?></td>
                                    <td><?=$row->qty?></td>
                                    <td><?=$row->bprice?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getItemInvoice(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button> 
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
                            <h4 class="modal-title">View / Invoice</h4>
                        </div>
                        <div class="modal-body clearfix">                           
                                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="pid" name="pid" required>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item</label>
                                        <div class="col-sm-10">
                                           <select class="form-control" required name="item_id" id="item_id">
                                                <option selected disabled value="">Select Item</option>
                                                <?php
                                                $select=mysql_query("SELECT * FROM ".$prefix."category");
                                                while($row=mysql_fetch_object($select)){?>
                                                <optgroup  label="<?=$row->name?>">
                                                <?php
                                                    $select1=mysql_query("SELECT * FROM ".$prefix."subcategory WHERE `cat_id`='".$row->id."'");
                                                    while($row1=mysql_fetch_object($select1)){
                                                    ?>
                                                    <option value="<?=$row1->id?>"><?=$row1->name?></option>
                                                    <?php }?>
                                                </optgroup>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Quantity</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="qty" id="stock" min="0" placeholder="Enter  Quantity" required>
                                        </div>
                                    </div>                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Base Price</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="bprice" id="price" min="0" placeholder="Enter Amount" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">IGST</label>
                                        <div class="col-sm-10">
                                            <input type="number" step="1" class="form-control" name="igst" id="igst" min="0"  max="100" placeholder="Enter IGST Percentage" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">CGST</label>
                                        <div class="col-sm-10">
                                            <input type="number" step="1" class="form-control" id="cgst" name="cgst" id="" min="0"  max="100" placeholder="Enter CGST Percentage" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">SGST</label>
                                        <div class="col-sm-10">
                                            <input type="number" step="1" class="form-control" id="sgst" name="sgst" id="" min="0"  max="100" placeholder="Enter SGST Percentage" required>
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
    {"searchable": false,"orderable": false,"targets": 3} ],
    "order": [[ 0, 'asc' ]]
});
$('[name="item_id"]').change(function(event) {
    $this = $(this);
    $.ajax({
        url: 'ajax/getItemPrice.php',
        type: 'POST',
        data: {id: $(this).val()},
        success:function(data){
            data = JSON.parse(data);
            $this.parents('form').find('[name="qty"]').attr('max', data[0]['stock']);
            $this.parents('form').find('[name="bprice"]').val(data[0]['bp']);
            $this.parents('form').find('[name="igst"]').val(data[0]['ig']);
            $this.parents('form').find('[name="cgst"]').val(data[0]['cg']);
            $this.parents('form').find('[name="sgst"]').val(data[0]['sg']);
        }
    });
    
});

function getItemInvoice(id){
    $.ajax({
        type: 'post',
        url:'ajax/getPurchaseInvoice.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#stock').val(data[0]['stock']);
            $('#item_id').val(data[0]['item_id']);
            $('#price').val(data[0]['price']);
            $('#igst').val(data[0]['igst']);
            $('#cgst').val(data[0]['cgst']);
            $('#sgst').val(data[0]['sgst']);
            
            
            $('.modal').modal('show');
        }
    })
}
</script>