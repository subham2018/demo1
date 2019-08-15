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
            	<p class="page-title"><i class="fa fa-inr"></i> Purchaser Invoice</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Purchaser Invoice</li>
                </ol>
            </div>
             <!--main content start-->
            <div class="main-body">
                <?php

                if(isset($_POST['submit'])){            
                    //check and insert banner
                    $pname=anything('purchase','name',$_POST['pid']);
                    $pemail=anything('purchase','email',$_POST['pid']);
                    $pphone=anything('purchase','phone',$_POST['pid']);

                    $s = true;
                    $query='';
                    foreach($_POST as $key=>$value){
                        if($s){
                            if($key != 'submit') $query .="`$key`='$value',";
                        }
                        else break;
                        if($key == 'pid') $s=false;
                    }
                    $query .= "`terms`='".$_POST['term']."',`account`='".$_POST['account']."'";
                    
                    if(mysqli_query($link,"INSERT INTO ".$prefix."purcheser_bill SET `pname`='".$pname."',`pemail`='".$pemail."',`pphone`='".$pphone."', ".$query)){
                       $id=mysqli_insert_id($link);
                        foreach ($_POST['item'] as $key => $value) {
                            $iname=anything('subcategory','name',$value);
                            $ides=anything('subcategory','dsc',$value);

                            if(is_numeric($value))
                                $query = "`inv_id`='".$id."',`item_id`='".$value."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                            else $query = "`inv_id`='".$id."',`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";

                            mysqli_query($link,"INSERT INTO ".$prefix."purcheser_invoice SET ".$query);
                            if(is_numeric($value)){
                                mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='cleared',`ref_id`='".mysqli_insert_id($link)."'");

                                $stock = floatval(anything('subcategory','stock',$value)) - floatval($_POST['qty'][$key]);
                                mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                            }

                        }echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Invoice added successfully.</p>';
                    }
                    else {
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while adding!</p>'.mysqli_error(); 
                    }
                    
                }


                if(isset($_POST['usubmit'])){            
                    //check and insert banner
                    $pname=anything('purchase','name',$_POST['pid']);
                    $pemail=anything('purchase','email',$_POST['pid']);
                    $pphone=anything('purchase','phone',$_POST['pid']);

                    $s = true;
                    $query='';
                    foreach($_POST as $key=>$value){
                        if($s){
                            if($key != 'usubmit') $query .="`$key`='$value',";
                        }
                        else break;
                        if($key == 'pid') $s=false;
                    }
                    $query .= "`terms`='".$_POST['term']."',`account`='".$_POST['account']."'";
                    
                    if(mysqli_query($link,"UPDATE ".$prefix."purcheser_bill SET `pname`='".$pname."',`pemail`='".$pemail."',`pphone`='".$pphone."', ".$query." WHERE `id`='".$_POST['inv_id']."'")){
                       $id=$_POST['inv_id'];
                        foreach ($_POST['item'] as $key => $value) {
                            $iname=anything('subcategory','name',$value);
                            $ides=anything('subcategory','dsc',$value);

                            

                            if($_POST['iid'][$key] == ''){
                                if(is_numeric($value))
                                    $query = "`inv_id`='".$id."',`item_id`='".$value."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                                else $query = "`inv_id`='".$id."',`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";

                                mysqli_query($link,"INSERT INTO ".$prefix."purcheser_invoice SET ".$query);
                                array_push($_POST['iid'], mysql_insert_id());
                                
                                if(is_numeric($value)){
                                    
                                    
mysqli_query($link,"INSERT INTO ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='cleared',`inv_id`='".$id."',`ref_id`='".mysqli_insert_id($link)."'");
    
                                    $stock = floatval(anything('subcategory','stock',$value)) - floatval($_POST['qty'][$key]);
                                    mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                }
                            }
                            else{
                                $prev_item = row('purcheser_invoice',$_POST['iid'][$key]);
                                if(is_numeric($value))
                                    $query = "`item_id`='".$value."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";
                                else $query = "`item_id`='".$value.'|'.$_POST['ptype'][$key]."',`bprice`='".$_POST['rate'][$key]."',`sgst`='".$_POST['sgst'][$key]."',`igst`='".$_POST['igst'][$key]."',`cgst`='".$_POST['cgst'][$key]."',`item_name`='".$iname."',`item_desc`='".$ides."',`qty`='".$_POST['qty'][$key]."',`unit`='".$_POST['unit'][$key]."',`hsn`='".$_POST['hsn'][$key]."'";

                                mysqli_query($link,"UPDATE ".$prefix."purcheser_invoice SET ".$query." WHERE `id`='".$_POST['iid'][$key]."'");

                                if(is_numeric($value)){
                                    mysqli_query($link,"UPDATE ".$prefix."stock_manage SET `sc_id`='".$value."',`stock`='".$_POST['qty'][$key]."',`type`='cleared', `inv_id`='".$id."' WHERE `ref_id`='".$_POST['iid'][$key]."'");

                                    if($prev_item->item_id == $value){
                                        $stock = floatval(anything('subcategory','stock',$value)) + floatval($prev_item->qty) - floatval($_POST['qty'][$key]);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                    }else{
                                        $stock = floatval(anything('subcategory','stock',$prev_item->item_id)) + floatval($prev_item->qty);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$prev_item->item_id);

                                        $stock = floatval(anything('subcategory','stock',$value))  - floatval($_POST['qty'][$key]);
                                        mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$value);
                                    }
                                }
                            }

                        }
                        
                        $s = mysqli_query($link,"SELECT * FROM `".$prefix."purcheser_invoice` where `inv_id`='".$_POST['inv_id']."'");
                        while ($r=mysqli_fetch_object($s)) {
                           if(!in_array($r->id, $_POST['iid'])){
                                $stock = floatval(anything('subcategory','stock',$r->item_id)) + floatval($r->qty);
                                mysqli_query($link,"UPDATE ".$prefix."subcategory SET `stock`='".$stock."' WHERE `id`=".$r->item_id);
                                mysqli_query($link,"DELETE FROM `".$prefix."purcheser_invoice` where `id`='".$r->id."'");
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
                    
                    if(mysqli_query($link,"UPDATE ".$prefix."purcheser_bill SET `cancel`='1' WHERE id=".variable_check($_GET['del']))){ //delete info
                mysqli_query($link,"DELETE  FROM ".$prefix."purcheser_invoice WHERE inv_id=".variable_check($_GET['del']));
                 mysqli_query($link,"DELETE  FROM ".$prefix."stock_manage WHERE inv_id=".variable_check($_GET['del']));
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i> Cancelled successfully.</p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
               
                ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Purchaser Invoice List <a href="pinvoice.php" class="btn btn-sm btn-default pull-right">Create Invoice</a></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                   <th>Invoice No.</th> 
                                   <th>Challan</th> 
                                   <th>Purchaser Name</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Paying Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_bill");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->bill?></td>
                                    <td><?=$row->challan?></td>
                                    <td><?=$row->pname?></td>
                                    <td><?=$row->pphone?></td>
                                    
                                    <td><?=date('Y-m-d',strtotime($row->date))?></td>
                                    <td class="bg-<?=InvoicePaid($row->id,'p')=='Paid'?'success':(InvoicePaid($row->id,'p')=='Unpaid'?'danger':'warning')?>"><?=InvoicePaid($row->id,'p')?></td>
                                    <td>
                                        <?php if($row->cancel=='0'){?>
                                        <a class="btn btn-default btn-sm" href="pinvoice_edit.php?id=<?=$row->id?>"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></a>                                                                                                              
                                       <a class="btn btn-default btn-sm" title="Manage Payment" href="payment.php?id=<?=$row->id?>&t=p"><i class="fa fa-inr fa-fw"></i></a> 
                                        <a class="btn btn-danger btn-sm" title="Cancel" href="?del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
                                        <?php }?>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-fw fa-print"></i> <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="sales_print.php?id=<?=$row->id?>&t=p" target="_blank">Proforma Copy</a></li>
                                            <li><a href="sales_print.php?id=<?=$row->id?>&t=b" target="_blank">Buyer's Copy</a></li>
                                            <li><a href="sales_print.php?id=<?=$row->id?>&t=s" target="_blank">Seller's Copy</a></li>
                                            <li><a href="sales_print.php?id=<?=$row->id?>&t=t" target="_blank">Triplicate Seller's Copy</a></li>
                                            <li><a href="sales_print.php?id=<?=$row->id?>&t=s&d=1" target="_blank">Transporter's Copy</a></li>
                                          </ul>
                                        </div>
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
    "order": [[ 0, 'desc' ]]
});

 $('#date,#dat').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
    

function getSellingInvoice(id){
    $.ajax({
        type: 'post',
        url:'ajax/getPurchesBill.php',
        data:{'id':id},
        success:function(data){
            alert(data)
            data = JSON.parse(data);
            $('#bid').val(id);
            $('#pid').val(data[0]['pid']);
            $('#challan').val(data[0]['challan']);
            $('#order_id').val(data[0]['order_id']);
            $('#note').val(data[0]['note']);
            $('#terms').val(data[0]['terms']);
            $('#total').val(data[0]['total']);

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