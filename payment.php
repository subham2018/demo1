<?php include_once 'include/head.php';
if(isset($_GET['id'],$_GET['t'])){
    $id = $_GET['id'];
    $type = $_GET['t']=='p'?"pb_id":"sb_id";
    $query = $_GET['t']=='p'?" where `pb_id`='$id'":" where `sb_id`='$id'";
}else header('location: index.php');
?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.standalone.min.css">
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
            	<p class="page-title"><i class="fa fa-inr fa-fw"></i> Payment</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Payment</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                   
                //seller Page Save
                if(isset($_POST['submit'])){
                    


                    $query1='';

                    foreach ($_POST as $key => $value)if($key  !=  'submit')if($key  !='qa')$query1 .="`$key`='$value',";
                    $query1 = substr($query1, 0, -1);//omit last comma

                    if( mysql_query("INSERT INTO ".$prefix."payment SET ".$query1)) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Added successfully.</p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error!</p>';   
                    }
                }

                if(isset($_GET['del'])){
                    //delete Projects
                    
                    if(mysql_query("UPDATE ".$prefix."payment SET `del`='1' WHERE id=".variable_check($_GET['del']))){ //delete info

                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>   Deleted successfully.</p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
                ?>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php
                        $total=mysql_fetch_object(mysql_query("SELECT sum(`amount`) as 'amt' FROM ".$prefix."payment".$query." AND `del`='0'"));
                        ?>
                        <div class="row">
                            <div class="col-sm-3"><div class="panel-title">Invoice ID: <b><?=$id?></b></div></div>
                            <div class="col-sm-3"><div class="panel-title">Total: <b><small><i class="fa fa-inr fa-fw"></i></small><?=InvoiceTotal($id,$_GET['t'])?></b></div></div>
                            <div class="col-sm-3"><div class="panel-title">Paid: <b><small><i class="fa fa-inr fa-fw"></i></small><?=$total->amt==''?'0':$total->amt?></b></div></div>
                            <div class="col-sm-3"><div class="panel-title">To be Paid (Due): <b><small><i class="fa fa-inr fa-fw"></i></small><?=InvoiceTotal($id,$_GET['t'])-$total->amt?></b></div></div>
                        </div>
                        
                    </div>
                </div>
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Payment</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$type?>" value="<?=$id?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Amount</label>
                                <div class="col-sm-9">
                                    <input type="number" min="0" step="1.00" class="form-control" name="amount"  placeholder="Enter Amount" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="date"  placeholder="Enter Date" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="mode"  placeholder="Enter Mode" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Received By</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="rby"  placeholder="Enter Name" required>
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Payment List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Payment</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Received By</th>
                                    <th>Mode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysql_query("SELECT * FROM ".$prefix."payment".$query);
                                while($row=mysql_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->del=='1'?'<del>':''?><?=$row->date?><?=$row->del=='1'?'</del>':''?></td>
                                    <td><?=$row->del=='1'?'<del>':''?><?=$row->amount?><?=$row->del=='1'?'</del>':''?></td>
                                    <td><?=$row->del=='1'?'<del>':''?><?=$row->rby?><?=$row->del=='1'?'</del>':''?></td>
                                    <td><?=$row->del=='1'?'<del>':''?><?=$row->mode?><?=$row->del=='1'?'</del>':''?></td>
                                    <td>
                                        <?php if($row->del=='0'){?>
                                        <a class="btn btn-danger btn-sm" href="?del=<?=$row->id?>&t=<?=$_GET['t']?>&id=<?=$_GET['id']?>"><i class="fa fa-trash fa-fw"></i></a>
                                        <?php }else echo 'Payment Cancelled';?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">

$('[name="date"]').datepicker({format:'yyyy-mm-dd', endDate:'0d'});

$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 4} ],
    "order": [[ 0, 'asc' ]]
});
</script>

<?php if(isset($_GET['del'])){?>
<div id="url_replace">
    <script type="text/javascript">
        $(document).ready(function(e) {
            window.history.replaceState(null, null, '<?=$page.'?id='.$_GET['id'].'&t='.$_GET['t']?>');
            $('#url_replace').remove();
        });
    </script>
</div>
<?php }?>
</body>
</html>