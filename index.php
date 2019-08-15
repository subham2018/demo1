<?php include_once 'include/head.php';
error_reporting(0);?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">

</head>

<body>
<?php include_once 'include/navbar.php';?>	
        <div class="content">
        	<div class="overlay"></div><!--for mobile view sidebar closing-->
        	<!--title and breadcrumb-->
            <div class="top">
            	<p class="page-title">Dashboard</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Admin Panel</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </div>
            <!--main content start-->
             <div class="main-body">
                <?php if($user_type=='Admin'){?>
                <div class="panel panel-default">
                    <div class="panel-body">      
                          
                          
                        <div class="row">
                            <div class="col-sm-4">
                                <a  class="box red">
                                    <?php 
                                    $select=mysqli_num_rows(mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_manage"));
                                    ?>
                                <h1><?=$select?></h1>                            
                                   <p>Warehouses</p>

                                </a>
                            </div>
                            <div class="col-sm-4">
                                 <a  class="box yellow">
                                    <?php 
                                    $select1=mysqli_fetch_object(mysqli_query($link,"SELECT SUM(`stock`) as `tsi` FROM ".$prefix."stock_manage WHERE `type`='infused' AND (`date` between '".date('Y-m-01 00:00:00')."' AND '".date('Y-m-t 23:59:59')."') "));
                                    ?>
                                 <h1><?=isset($select1->tsi)?$select1->tsi:0?></h1>                       
                                    <p>Monthly Stock Infused</p>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a class="box green">
                                    <?php 
                                    $select2=mysqli_fetch_object(mysqli_query($link,"SELECT SUM(`stock`) as `tsc` FROM ".$prefix."stock_manage WHERE `type`='cleared' AND (`date` between '".date('Y-m-01 00:00:00')."' AND '".date('Y-m-t 23:59:59')."') "));
                                    ?>
                                 <h1><?=isset($select2->tsc)?$select2->tsc:0?></h1>                           
                                    <p>Monthly Stock Clearance</p>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a  class="box blue">
                                    <?php 
                                    $select=mysqli_num_rows(mysqli_query($link,"SELECT * FROM ".$prefix."subcategory WHERE `stock`='0' AND `del`='0'"));
                                    ?>
                                <h1><?=$select?></h1>
                                    <p>Out of Stock Products</p>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a  class="box orange">
                                    <?php 
                                     $cost=mysqli_fetch_object(mysqli_query($link,"SELECT SUM(((`bprice`*`qty`)*`igst`/100)+((`bprice`*`qty`)*`sgst`/100)+((`bprice`*`qty`)*`cgst`/100)) as 'cost' FROM ".$prefix."purcheser_invoice WHERE `inv_id` IN (SELECT `id` FROM ".$prefix."purcheser_bill WHERE `bdate` between '".date('Y-m-01')."' and '".date('Y-m-t')."')"));
                                     echo mysqli_error();
                                    ?>
                                <h1><?=isset($cost->cost)?number_format($cost->cost,2):0?></h1>                           
                                    <p>Monthly GST OUTPUT</p>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a  class="box purple">
                                    <?php 
                                     $cost=mysqli_fetch_object(mysqli_query($link,"SELECT SUM(((`price`*`stock`)*`igst`/100)+((`price`*`stock`)*`sgst`/100)+((`price`*`stock`)*`cgst`/100)) as 'cost' FROM ".$prefix."item_invoice WHERE `seller_bill` IN (SELECT `id` FROM ".$prefix."seller_invoice WHERE `bdate` between '".date('Y-m-01')."' and '".date('Y-m-t')."')"));
                                    ?>
                                <h1><?=isset($cost->cost)?number_format($cost->cost,2):0?></h1>                            
                                    <p>Monthly GST INPUT</p>
                                </a>
                            </div>
                           
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="seller.php" target="_blank"><i class="fa fa-users fa-fw"></i> <span>Manage Sellers</span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="stock_transfer.php" target="_blank"><i class="fa fa-sitemap fa-fw"></i> <span> Manage Stock Transfer</span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="warehouse_manage.php" target="_blank"><i class="fa fa-university fa-fw"></i> <span> Manage Warehouse</span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="category.php" target="_blank"><i class="fa fa-cubes fa-fw"></i> <span> Products</span></a>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                              $select=mysqli_num_rows(mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_bill WHERE `cancel`='0' AND `id` not in (SELECT `pb_id` FROM ".$prefix."payment)"));
                            ?>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="purcheser_bill.php" target="_blank"><i class="fa fa-money fa-fw"></i> <span> Unpaid Purchaser : <b> <?=$select?> </b></span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="purchaser.php" target="_blank"><i class="fa fa-user fa-fw"></i> <span>Manage Purchaser</span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="selling_invoice.php" target="_blank"><i class="fa fa-pencil-square-o fa-fw"></i> <span> Seller Billing</span></a>
                            </div>
                            <div class="col-sm-3 text-center">
                                  <a class="btn btn-default box" href="purcheser_bill.php" target="_blank"><i class="fa fa-inr fa-fw"></i> <span> Purchaser Billing</span></a>
                            </div>
                        </div>
                        <hr>
                        <?php
                        $select=mysqli_query($link,"SELECT * FROM ".$prefix."certificate");
                        while($row=mysqli_fetch_object($select)){
                            $cyear = explode('-', $row->cdate);
                            $pyear = explode('-', $row->pdate);
                           
                        if ($cyear[3]="2019" || $pyear[3]="2019"){?>
                        
                        <p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> <strong>Alert!</strong> Certificate ID <?=$row->id?> will expire on <?=$row->cdate?> <button class="btn btn-default btn-xs pull-right" onClick="getData(<?=$row->id?>)">Renew Now</button></p>
                        <?php }}?>
                    </div>
                </div>
                <?php }else{?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <a  class="box red">
                                    <?php 
                                    $select=mysqli_fetch_object(mysqli_query($link,"SELECT sum(`stock`) as 'ts' FROM ".$prefix."stock_transfer WHERE `w_id`='".$user->id."' AND `type`='send'"));
                                    ?>
                                <h1><?=$select->ts==''?'0':$select->ts?></h1>                            
                                   <p>Stock Received</p>

                                </a>
                            </div>
                            <div class="col-sm-6">
                                 <a  class="box yellow">
                                    <?php 
                                    $select=mysqli_fetch_object(mysqli_query($link,"SELECT sum(`stock`) as 'ts' FROM ".$prefix."stock_transfer WHERE `w_id`='".$user->id."' AND `type`<>'send'"));
                                    ?>
                                <h1><?=$select->ts==''?'0':$select->ts?></h1>                        
                                    <p>Stock Transfer</p>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr >
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."warehouse_stock WHERE `w_id`='".$user->id."'");
                                while($row=mysqli_fetch_object($select)){
                                   
                                ?>
                                <tr class="<?=$row->stock < 5 ?'bg-danger':''?>">
                                    <td><?=anything('subcategory','name',$row->item_id)?></td>
                                    <td><?=$row->stock?></td>
                                   
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }?>
            </div>
            <!--main content end-->
        </div>

        <div class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">View / Edit Certificate</h4>
                </div>
                <div class="modal-body clearfix">

                    <form action="certificate.php" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="bid" name="bid" required>
                        <div class="row">
                           <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Certificate Name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address Line 1 </label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="address1" id="address1" placeholder="Enter Certificate Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address Line 2 </label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="address2" id="address2" placeholder="Enter Certificate Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address Line 3 </label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="address3" id="address3" placeholder="Enter Certificate Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">      
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Date</label>
                                <div class="col-sm-10"> 
                                    <input type="text" class="form-control" id="date1" name="date"  placeholder="Select date" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="type" name="type" required>
                                        <option selected disabled value="">Select Type</option>
                                        <option value="New">New</option>
                                        <option value="Refill">Refill</option>
                                        <option value="Both">New + Refill</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">      
                            <div class="form-group text-center">
                                <label class="col-sm-2 control-label">On Dated</label>
                                 <div class="col-sm-10">    
                                    <input type="text" class="form-control" id="odate1"  name="odate" placeholder="Select date" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">      
                            <div class="form-group text-center">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">   
                                    <textarea type="text" class="form-control" id="dsc1" name="dsc" placeholder="Enter Description here" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">      
                            <div class="form-group text-center">
                                <label class="col-sm-2 control-label">Pressure type Vaild upto</label>
                                 <div class="col-sm-10">    
                                    <input type="text" class="form-control" id="pdate1"  name="pdate" placeholder="Select date" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">      
                            <div class="form-group text-center">
                                <label class="col-sm-2 control-label">Cartridge type Vaild upto</label>
                                 <div class="col-sm-10">    
                                    <input type="text" class="form-control" id="cdate1"  name="cdate" placeholder="Select date" required>
                                </div>
                            </div>
                        </div>                            
                        </div>
                        <hr>
                        <input type="submit" name="usubmit" class="btn btn-success pull-right" value="Save">
                    </form>

                </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">



$(".list-cont").mCustomScrollbar({
      axis:"y",
      theme: "minimal-dark",
      scrollInertia: 100
});
</script>

</body>
</html>