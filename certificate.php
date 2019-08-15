<?php include_once 'include/head.php';?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">
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
            	<p class="page-title"><i class="fa fa-users fa-fw"></i> Certificate </p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Certificate </li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <?php
                   
                //seller Page Save
                if(isset($_POST['submit'])){
                    $query='';

                    foreach ($_POST as $key => $value)if($key  !=  'submit')if($key  !='qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    if( mysqli_query($link,"INSERT INTO ".$prefix."certificate  SET ".$query)) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Added successfully. <a target="new" class="btn btn-default btn-xs" href="certificate_print.php?id='.mysqli_insert_id($link).'"><i class="fa fa-fw fa-print"></i>Print Now</a></p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error!</p>';   
                    }
                }

                //seller Page edit
                if(isset($_POST['usubmit'])){             
                    //check and insert activity
                    
                    
                    $query='';
                    foreach($_POST as $key=>$value) if($key != 'usubmit')if($key != "bid")if($key != 'qa')$query .="`$key`='$value',";
                    $query = substr($query, 0, -1);//omit last comma

                    if( mysqli_query($link,"UPDATE ".$prefix."certificate  SET ".$query." WHERE `id`=".$_POST['bid'])) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Update successfully. <a target="new" class="btn btn-default btn-xs" href="certificate_print.php?id='.$_POST['bid'].'"><i class="fa fa-fw fa-print"></i>Print Now</a></p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error!</p>';   
                    }
                }

                ?>
                
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Certificate</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="row">
                                
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Select Company*</label>
                                        <div class="col-sm-10">
                                            
                                            <select name="compid" class="form-control" required>
                                                <option value="" default>Select Company Name</option>
                                                <?php 
                                                $compselect=mysqli_query($link,"SELECT `id`, `name` FROM ".$prefix."purchase");
                                                while($compfetch=mysqli_fetch_object($compselect))
                                                {?>
                                                <option value="<?=$compfetch->id?>"><?=$compfetch->name?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Name*</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name"  placeholder="Enter Certificate Name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 1 *</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address1" placeholder="Enter Certificate Address" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 2 </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address2" placeholder="Enter Certificate Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 3 </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address3" placeholder="Enter Certificate Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">From Date*</label>
                                        <div class="col-sm-10"> 
                                            <input type="text" class="form-control" id="date" name="date"  placeholder="Select date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Type*</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="type" required>
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
                                        <label class="col-sm-2 control-label">On Dated*</label>
                                         <div class="col-sm-10">    
                                            <input type="text" class="form-control" id="odate"  name="odate" placeholder="Select date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group text-center">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">   
                                            <textarea type="text" class="form-control" id="dsc" name="dsc" placeholder="Enter Description here" ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group text-center">
                                        <label class="col-sm-2 control-label">Pressure type Vaild upto</label>
                                         <div class="col-sm-10">    
                                            <input type="text" class="form-control" id="pdate"  name="pdate" placeholder="Select date" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group text-center">
                                        <label class="col-sm-2 control-label">Cartridge type Vaild upto*</label>
                                         <div class="col-sm-10">    
                                            <input type="text" class="form-control" id="cdate"  name="cdate" placeholder="Select date" required>
                                        </div>
                                    </div>
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Certificate List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Certificate</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Cert. ID</th>
                                    <th>Company ID</th>
                                    <th>Name</th>
                                    <th>On Date</th>
                                    <th>Type</th>
                                    <th>Pressure Type Validity</th>
                                    <th>Cartridge Type Validity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."certificate");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->id?></td>
                                    <td><?=$row->compid?></td>
                                    <td><?=$row->name?></td>
                                    <td><?=$row->odate?></td>
                                    <td><?=$row->type?></td>
                                    <td><?=$row->pdate?></td>
                                    <td><?=$row->cdate?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getCertificate(<?=$row->id?>)">Renew / Edit</button>                                       
                                       
                                        <a class="btn btn-success btn-sm" target="new" href="certificate_print.php?id=<?=$row->id?>">Print</a>
                                        <a class="btn btn-danger btn-sm" target="new" href="certificate_print.php?id=<?=$row->id?>&p=1">Get Certificate</a> 
                                        <a class="btn btn-warning btn-sm" target="new" href="certificate_duplicate.php?id=<?=$row->id?>&p=1">Duplicate Certificate</a> 
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="modal fade" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">View / Edit Certificateinput</h4>
                        </div>
                        <div class="modal-body clearfix">

                            <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="bid" name="bid" required>
                                <div class="row">
                                     <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Select Company*</label>
                                        <div class="col-sm-10">
                                            
                                            <select name="compid" class="form-control" id="compid" required>
                                                <option value="" default>Select Company Name</option>
                                                <?php 
                                                $compselect=mysqli_query($link,"SELECT `id`, `name` FROM ".$prefix."purchase");
                                                while($compfetch=mysqli_fetch_object($compselect))
                                                {?>
                                                <option value="<?=$compfetch->id?>"><?=$compfetch->name?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                   <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Name*</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Certificate Name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 1 *</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address1" id="address1" placeholder="Enter Certificate Address" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 2 </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address2" id="address2" placeholder="Enter Certificate Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Address Line 3 </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="address3" id="address3" placeholder="Enter Certificate Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Date*</label>
                                        <div class="col-sm-10"> 
                                            <input type="text" class="form-control" id="date1" name="date"  placeholder="Select date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Type*</label>
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
                                        <label class="col-sm-2 control-label">On Dated*</label>
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
                                        <label class="col-sm-2 control-label">Pressure type vaild upto</label>
                                         <div class="col-sm-10">    
                                            <input type="text" class="form-control" id="pdate1"  name="pdate" placeholder="Select date" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">      
                                    <div class="form-group text-center">
                                        <label class="col-sm-2 control-label">Cartridge type vaild upto*</label>
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
                
                
            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/ckeditor/ckeditor.js"></script>
<script src="assets/js/common.js"></script>


<script type="text/javascript">
CKEDITOR.replace( 'dsc');
CKEDITOR.replace( 'dsc1');
$('#date').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#odate').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#pdate').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#cdate').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#date1').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#odate1').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#pdate1').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});
$('#cdate1').datepicker({
    format:"MM-dd-yyyy",
    forceParse:false
});

$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 5} ],
    "order": [[ 0, 'asc' ]]
});
function getCertificate(id){
    $.ajax({
        type: 'post',
        url:'ajax/getCertificate.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#bid').val(id);
            $('#name').val(data[0]['name']);
            $('#compid').val(data[0]['compid']);
            $('#address1').val(data[0]['address1']);
            $('#address2').val(data[0]['address2']);
            $('#address3').val(data[0]['address3']);
            $('#date1').val(data[0]['date']);
            $('#odate1').val(data[0]['odate']);
            $('#cdate1').val(data[0]['cdate']);
            $('#pdate1').val(data[0]['pdate']);
            $('#type').val(data[0]['type']);
            CKEDITOR.instances['dsc1'].setData(data[0]['dsc']);    
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