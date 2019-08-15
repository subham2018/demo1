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
            	<p class="page-title"><i class="fa fa-pencil-square-o"></i> Seller Billing</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Seller Billing</li>
                </ol>
            </div>
             <!--main content start-->
            <div class="main-body">
                <?php

                if(isset($_POST['submit'])){          
                    //check and insert banner

                    $s = true;
                    $query='';
                    foreach($_POST as $key=>$value){
                        if($s){
                            if($key != 'submit') $query .="`$key`='$value',";
                        }
                        else break;
                        if($key == 'seller_id') $s=false;
                    }
                    
                    $query = substr($query, 0, -1);
                    
                    if(mysqli_query($link,"INSERT INTO ".$prefix."seller_invoice SET ".$query)){
                       $id=mysqli_insert_id($link);
                        foreach ($_POST['item'] as $key => $value) {

                            if(is_numeric($value))
                                $query = "`seller_bill`='".$id."',`item_id`='".$value."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                            else $query = "`seller_bill`='".$id."',`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";


                            mysqli_query($link,"INSERT INTO ".$prefix."item_invoice SET ".$query);

                            if(is_numeric($value)){
                                mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='purchase',`ref_id`='".mysqli_insert_id($link)."'");

                                $stock = floatval(anything('subcategory','stock',$value)) + floatval($_POST['qty'][$key]);
                                mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                            }
                        }
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Invoice added successfully.</p>';
                    }
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while adding!</p>'.mysqli_error(); 
                    }
                    
                }

                if(isset($_POST['usubmit'])){      

                    $s = true;
                    $query='';
                    foreach($_POST as $key=>$value){
                        if($s){
                            if($key != 'usubmit') $query .="`$key`='$value',";
                        }
                        else break;
                        if($key == 'seller_id') $s=false;
                    }

                    $query = substr($query, 0, -1);
                    
                    if(mysqli_query($link,"UPDATE ".$prefix."seller_invoice SET ".$query." WHERE `id`='".$_POST['inv_id']."'")){
                       $id=$_POST['inv_id'];
                        foreach ($_POST['item'] as $key => $value) {

                            //if(mysql_num_rows(mysql_query("SELECT `id` FROM ".$prefix."item_invoice WHERE `seller_bill`='".$id."' AND `item_id`='".$value."'")) == 0){
                            if($_POST['iid'][$key] == ''){
                                if(is_numeric($value))
                                    $query = "`seller_bill`='".$id."',`item_id`='".$value."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                                else $query = "`seller_bill`='".$id."',`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";

                                mysqli_query($link,"INSERT INTO ".$prefix."item_invoice SET ".$query);

                                array_push($_POST['iid'], mysqli_insert_id($link));

                                if(is_numeric($value)){
                                    mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='purchase',`ref_id`='".mysqli_insert_id($link)."'");
                                   

                                    $stock = floatval(anything('subcategory','stock',$value)) + floatval($_POST['qty'][$key]);
                                    mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                }
                            }
                            else{
                                $prev_item = row('item_invoice',$_POST['iid'][$key]);
                                if(is_numeric($value))
                                    $query = "`item_id`='".$value."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                                else $query = "`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`price`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`stock`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";

                                mysqli_query($link,"UPDATE ".$prefix."item_invoice SET ".$query." WHERE `id`='".$_POST['iid'][$key]."'");

                                if(is_numeric($value)){
                                    mysqli_query($link,"UPDATE ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='purchase' WHERE `ref_id`='".$_POST['iid'][$key]."'");

                                    if($prev_item->item_id == $value){
                                        $stock = floatval(anything('subcategory','stock',$value)) - floatval($prev_item->stock) + floatval($_POST['qty'][$key]);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                    }else{
                                        $stock = floatval(anything('subcategory','stock',$prev_item->item_id)) - floatval($prev_item->stock);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$prev_item->item_id);

                                        $stock = floatval(anything('subcategory','stock',$value))  + floatval($_POST['qty'][$key]);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                    }
                                }
                            }   

                        }
                        
                        $s = mysqli_query($link,"SELECT * FROM `".$prefix."item_invoice` where `seller_bill`='".$id."'");
                            
                        while ($r=mysqli_fetch_object($s)) {
                           if(!in_array($r->id, $_POST['iid'])){
                                $stock = floatval(anything('subcategory','stock',$r->item_id)) - floatval($r->stock);
                                mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$r->item_id);
                                mysqli_query($link,"DELETE FROM `".$prefix."item_invoice` where `id`='".$r->id."'");
                           }
                       }   
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Invoice updated successfully.</p>';
                    }
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while adding!</p>'.mysqli_error(); 
                    }
                }

                if(isset($_GET['del'])){
                    //delete Projects
                    
                    if(mysqli_query($link,"UPDATE ".$prefix."seller_invoice SET `cancel`='1' WHERE `id`='".variable_check($_GET['del'])."'")){ //delete info

                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Cancelled successfully.</p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
               
               
                ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Seller Billing List <a href="sinvoice.php" class="btn btn-sm btn-default pull-right"> Create Invoice</a></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Bill No.</th>
                                    <th>Order No.</th>
                                    <th>Seller Name</th>
                                    <th>Seller Location</th>
                                    <th>Date</th>
                                    <th>Paying Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."seller_invoice ORDER BY `id` DESC");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->bill?></td>
                                    <td><?=$row->order_id?></td>
                                    <td><?=anything('seller','name',$row->seller_id)?></td>
                                    <td><?=anything('seller','location',$row->seller_id)?></td>
                                    <td><?=$row->datetime?></td>
                                    <td class="bg-<?=InvoicePaid($row->id,'s')=='Paid'?'success':(InvoicePaid($row->id,'s')=='Unpaid'?'danger':'warning')?>"><?=InvoicePaid($row->id,'s')?></td>
                                    <td>
                                    <?php if($row->cancel=='0'){?>
                                    <a class="btn btn-default btn-sm" href="sinvoice_edit.php?id=<?=$row->id?>"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></a>
                                    <a class="btn btn-default btn-sm" title="Manage Payment" href="payment.php?id=<?=$row->id?>&t=s"><i class="fa fa-inr fa-fw"></i></a> 

                                    

                                                                                                                   
                                    
                                    <a class="btn btn-danger btn-sm" title="Cancel" href="?del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
                                    <?php }?>
                                        <a class="btn btn-success btn-sm" href="purchase_print.php?id=<?=$row->id?>" target="_blank"><i class="fa fa-fw fa-print"></i></a>  
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--main content end-->
            </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">

$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 6} ],
    "order": [[ 4, 'desc' ]]
});

 $('#date,#dat').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
    });
    

function getSellingInvoice(id){
    $.ajax({
        type: 'post',
        url:'ajax/getSellingInvoice.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#pid').val(id);
            $('#seller_id').val(data[0]['seller_id']);
            $('#in_number').val(data[0]['in_number']);
            $('#hsn').val(data[0]['hsn']);
            $('#sac').val(data[0]['sac']);
            $('#gst').val(data[0]['gst']);
            $('#amount').val(data[0]['amount']);
            $('#date').val(data[0]['date']);
           
            
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