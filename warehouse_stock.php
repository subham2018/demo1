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
            	<p class="page-title"><i class="fa fa-cubes"></i> Warehouse Stock</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Warehouse Stock</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Ware House Stock List </div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr >
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysql_query("SELECT * FROM ".$prefix."warehouse_stock WHERE `w_id`='".$user->id."'");
                                while($row=mysql_fetch_object($select)){
                                   
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
               
                
                
            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">

$('#Projects_list').DataTable( {
    
    
});

</script>