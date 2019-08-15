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
            	<p class="page-title"><i class="fa fa-truck"></i> Stock Transfer</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Stock Transfer</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                //Projects Save
                if(isset($_POST['submit'])){
                     //prevent mysql injection              
                    //check and insert banner
                    
                    if($user_type=='Admin') $_POST['type']='send';
                    else {
                        $_POST['type']='received';
                        $_POST['w_id']=$user_id;
                    }

                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'submit')if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma
                    

                    if(mysqli_query($link,"INSERT INTO ".$prefix."stock_transfer SET ".$query)){

                        mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$_POST['item_id']."',`stock`='".$_POST['stock']."',`t_type`='".$_POST['type']."',`type`='transfer',`ref_id`='".mysql_insert_id()."'");

                        $s=mysqli_num_rows(mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_stock WHERE `w_id`='".$_POST['w_id']."' AND `item_id`='".$_POST['item_id']."' "));
                        if($s>0){
                            $ss=mysqli_fetch_object(mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_stock WHERE `w_id`='".$_POST['w_id']."' AND `item_id`='".$_POST['item_id']."' "));
                            $stk=intval($ss->stock) + intval($_POST['stock']);
                             mysqli_query($link,"UPDATE ".$prefix."warehouse_stock SET `stock`='".$stk."' WHERE `id`='".$ss->id."'");
                                //echo mysql_error();
                                $stock = intval(anything('subcategory','stock',$_POST['item_id'])) - intval($_POST['stock']);
                                mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);
                        }else{
                             mysqli_query($link,"INSERT INTO ".$prefix."warehouse_stock SET `w_id`='".$_POST['w_id']."',`item_id`='".$_POST['item_id']."',`stock`='".$_POST['stock']."' ");
                             
                                //echo mysql_error();
                                 $stock = intval(anything('subcategory','stock',$_POST['item_id'])) - intval($_POST['stock']);
                                 mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);
                        }
                        

                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Stock Added successfully.</p>';
                    }
                    
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while uploading image!</p>'; 
                    }
                    
                }

                //Project edit
                if(isset($_POST['usubmit'])){
                     //prevent mysql injection              
                    $_POST['item_id'] = anything('stock_manage','sc_id',$_POST['pid']);
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'usubmit')if($key != "pid")if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    if(mysqli_query($link,"UPDATE ".$prefix."stock_transfer SET ".$query." WHERE `id`=".$_POST['pid'])){ //insert all text info
                        mysqli_query($link,"UPDATE ".$prefix."stock_manage SET `sc_id`='".$_POST['item_id']."',`stock`='".$_POST['stock']."' WHERE `ref_id`='".$_POST['pid']."'");

                         $ss=mysqli_fetch_object(mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_stock WHERE `w_id`='".$_POST['w_id']."' AND `item_id`='".$_POST['item_id']."' "));

                         mysqli_query($link,"UPDATE ".$prefix."warehouse_stock SET `stock`='".$_POST['stock']."' WHERE `id`='".$ss->id."'");
                        //echo mysql_error();
                        $stock = intval(anything('subcategory','stock',$_POST['item_id']))+intval($ss->stock) - intval($_POST['stock']);
                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$_POST['item_id']);


                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Stock Updated successfully.</p>';
                    }
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while updating details!</p>';   
                    }
                }
               
                ?>
                 <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Stock Transfer</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <?php if($user_type=='Admin'){?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Warehouse</label>
                                <div class="col-sm-10">
                                   <select class="form-control" required name="w_id" id="">
                                        <option selected disabled value="">Select Warehouse</option>
                                        <?php
                                        $select=mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_manage");
                                        while($row=mysqli_fetch_object($select)){
                                        ?>
                                        <option value="<?=$row->id?>"><?=$row->name.'['.$row->address.']'?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Item</label>
                                <div class="col-sm-10">
                                   <select class="form-control" required name="item_id" id="">
                                        <option selected disabled value="">Select Item</option>
                                        <?php
                                        $select=mysqli_query($link,"SELECT * FROM ".$prefix."category");
                                        while($row=mysqli_fetch_object($select)){?>
                                        <optgroup  label="<?=$row->name?>">
                                        <?php
                                            $select1=mysqli_query($link,"SELECT * FROM ".$prefix."subcategory WHERE `del`='0' AND `cat_id`='".$row->id."'");
                                            while($row1=mysqli_fetch_object($select1)){
                                            ?>
                                            <option value="<?=$row1->id?>"><?=$row1->name?></option>
                                            <?php }?>
                                        </optgroup>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Stock</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="stock" id="stock1" maxlength="" placeholder="Enter stock" required>
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Stock Transfer List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right"> Transfer</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query=$user_type=='WH'?" WHERE `w_id`='".$user->id."'":"";

                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."stock_transfer".$query);
                                while($row=mysqli_fetch_object($select)){
                                    if($user_type=='WH'){
                                        $type=$row->type=='send'? 'Received':'Send';
                                    }
                                    else $type=$row->type;
                                ?>
                                <tr>
                                    <td><?=anything('subcategory','name',$row->item_id)?></td>
                                    <td><?=$row->stock?></td>
                                    <td><?=$type?></td>
                                    <td><?=$row->datetime?></td>
                                    <td>
                                        <?php if(($user_type=='WH' && $row->type=='received') || $user_type=='Admin'){?>
                                        <button class="btn btn-default btn-sm" onClick="getStockTransfer(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button>
                                        <?php }?>                                                                   
                                       
                                        
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
                            <h4 class="modal-title">View / Stock Transfer</h4>
                        </div>
                        <div class="modal-body clearfix">                           
                          
                                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="pid" name="pid" required>
                                    <?php if($user_type=='Admin'){?>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Warehouse</label>
                                        <div class="col-sm-9">
                                           <select class="form-control" required name="w_id" id="w_id">
                                                <option selected disabled value="">Select Warehouse</option>
                                                <?php
                                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_manage");
                                                while($row=mysqli_fetch_object($select)){
                                                ?>
                                                <option value="<?=$row->id?>"><?=$row->name.'['.$row->address.']'?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php }?>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Item</label>
                                        <div class="col-sm-9">
                                           <select class="form-control"  disabled="disabled"  id="item_id" readonly>
                                                <option selected disabled value="">Select Item</option>
                                                <?php
                                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."category");
                                                while($row=mysqli_fetch_object($select)){?>
                                                <optgroup  label="<?=$row->name?>">
                                                <?php
                                                    $select1=mysqli_query($link,"SELECT * FROM ".$prefix."subcategory WHERE `del`='0' AND `cat_id`='".$row->id."'");
                                                    while($row1=mysqli_fetch_object($select1)){
                                                    ?>
                                                    <option value="<?=$row1->id?>"><?=$row1->name?></option>
                                                    <?php }?>
                                                </optgroup>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Stock</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="stock" id="stock" max="" placeholder="Enter stock" required>
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
    {"searchable": false,"orderable": false,"targets": 4} ],
    "order": [[ 3, 'desc' ]]
});
$('[name="item_id"]').change(function(event) {
    $this = $(this);
    $.ajax({
        url: 'ajax/getItemPrice.php',
        type: 'POST',
        data: {id: $(this).val()},
        success:function(data){
            data = JSON.parse(data);
            $this.parents('form').find('[name="stock"]').attr('max', data[0]['stock']);
        }
    });
    
});
function getStockTransfer(id){
    $.ajax({
        type: 'post',
        url:'ajax/getStockTransfer.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#w_id').val(data[0]['w_id']);
            $('#item_id').val(data[0]['item_id']).trigger('change');
            $('#stock').val(data[0]['stock']);
            
            $('.modal').modal('show');
        }
    })
}
</script>