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
            	<p class="page-title"><i class="fa fa-users fa-fw"></i> Seller</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Seller</li>
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

                            if( mysqli_query($link,"INSERT INTO ".$prefix."seller SET ".$query)) //insert all text info
                                echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Added successfully.</p>';
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

                    if( mysqli_query($link,"UPDATE ".$prefix."seller SET ".$query." WHERE `id`=".$_POST['bid'])) //insert all text info
                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>Update successfully.</p>';
                    else { 
                        echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error!</p>';   
                    }
                }
                if(isset($_GET['del'])){
                    //delete Projects
                    
                    if(mysqli_query($link,"DELETE FROM ".$prefix."seller WHERE id=".variable_check($_GET['del']))){ //delete info

                        echo '<p class="alert alert-success"><i class="fa fa-check fa-fw"></i>   Deleted successfully.</p>';
                    }
                    else echo '<p class="alert alert-danger"><i class="fa fa-warning fa-fw"></i> Sorry! There is an error while deleting!</p>';
                }
                ?>
                
                <!--add new-->
                <div class="panel panel-default" id="addnew" style="display:none;">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-plus fa-fw"></i> Add New Seller</div>
                    </div>
                    <div class="panel-body">
                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Name*</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="name"  placeholder="Enter Seller Name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Business Name*</label>
                                        <div class="col-sm-8">
                                            <input type="tel" class="form-control" name="business"  placeholder="Enter Seller Business Name " required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Business Address*</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="address" placeholder="Enter Seller Address" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Location*</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="location"  id="loc" placeholder="Enter Seller location" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Email(s)</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="email"  placeholder="Enter Seller Emails (separate by comma)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Phone No</label>
                                        <div class="col-sm-8">
                                            <input type="tel" class="form-control" name="phone" placeholder="Enter Seller Phone No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="tel" class="form-control" name="mobile" placeholder="Enter Seller Mobile No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Alt. Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="tel" class="form-control" name="amobile" placeholder="Enter Seller Alt. Mobile No">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">GST IN*</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="gstin" placeholder="Enter Seller GST IN " required>
                                        </div>
                                    </div> 
                                </div>     
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">VAT NO</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="vat" placeholder="Enter Seller VAT no " >
                                        </div>
                                    </div> 
                                </div>     
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">CST NO</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="cst" placeholder="Enter Seller CST no " >
                                        </div>
                                    </div> 
                                </div>     
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">PAN NO</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="pan" placeholder="Enter Seller PAN no " >
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
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Seller List <button onClick="$('#addnew').show('slow')" class="btn btn-sm btn-primary pull-right">Add Seller</button></div>
                    </div>
                    <div class="panel-body">
                        <table id="Projects_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone No</th>
                                    <th>Business Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."seller");
                                while($row=mysqli_fetch_object($select)){
                                ?>
                                <tr>
                                    <td><?=$row->name?></td>
                                    <td><?=$row->email?></td>
                                    <td><?=$row->phone?></td>
                                    <td><?=$row->business?></td>
                                    <td>
                                        <button class="btn btn-default btn-sm" onClick="getSeller(<?=$row->id?>)"><i class="fa fa-eye fa-fw"></i>/<i class="fa fa-pencil fa-fw"></i></button>                                       
                                       
                                        <a class="btn btn-danger btn-sm" href="?del=<?=$row->id?>"><i class="fa fa-trash fa-fw"></i></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="modal fade"  role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">View / Edit Seller</h4>
                        </div>
                        <div class="modal-body clearfix">

                            <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="bid" name="bid" required>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Name*</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Seller Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Business Name*</label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" name="business" id="bname"  placeholder="Enter Seller Business Name " required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">                
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Business Address*</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="address" id="add" placeholder="Enter Seller Address" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">                
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Location*</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="location"  id="loc1" placeholder="Enter Seller location" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Email(s)</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="email" id="email"  placeholder="Enter Seller Emails (separate by comma)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Phone No</label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter Seller Phone No">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Mobile No</label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" name="mobile" id="mobile" placeholder="Enter Seller Mobile No">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Alt. Mobile No</label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" name="amobile" id="amobile" placeholder="Enter Seller Alt. Mobile No">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">GST IN*</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="gstin" id="gst" placeholder="Enter Seller GST IN " required>
                                            </div>
                                        </div> 
                                    </div>     
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">VAT NO</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="vat" id="vat" placeholder="Enter Seller VAT no " >
                                            </div>
                                        </div> 
                                    </div>     
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">CST NO</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="cst" id="cst" placeholder="Enter Seller CST no " >
                                            </div>
                                        </div> 
                                    </div>     
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">PAN NO</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="pan" id="pan" placeholder="Enter Seller PAN no " >
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
<script src="assets/js/common.js"></script>
<script src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyCm2TyXncpU8er_lypzGpwrOOlxz_I_g3M"></script>
<script type="text/javascript">

    var autocomplete3 = new google.maps.places.Autocomplete(document.getElementById('loc'), {types: ['(cities)']}); 
    var autocomplete4 = new google.maps.places.Autocomplete(document.getElementById('loc1'), {types: ['(cities)']}); 
</script>
<script type="text/javascript">


$('#Projects_list').DataTable( {
    "columnDefs": [
    {"searchable": false,"orderable": false,"targets": 4} ],
    "order": [[ 0, 'asc' ]]
});
function getSeller(id){
    $.ajax({
        type: 'post',
        url:'ajax/getSeller.php',
        data:{'id':id},
        success:function(data){
            data = JSON.parse(data);
            $('#bid').val(id);
            $('#name').val(data[0]['name']);
            $('#email').val(data[0]['email']);
            $('#phone').val(data[0]['phone']);
            $('#mobile').val(data[0]['mobile']);
            $('#amobile').val(data[0]['amobile']);
            $('#add').val(data[0]['address']);
            $('#bname').val(data[0]['business']);
            $('#gst').val(data[0]['gstin']);
            $('#vat').val(data[0]['vat']);
            $('#cst').val(data[0]['cst']);
            $('#pan').val(data[0]['pan']);
            $('#loc1').val(data[0]['location']);           
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