<?php include "include/head.php";?>
<!doctype html>
<html>
<head>
    <?php include "include/header.php";?>
    <title><?=$site->name?> </title>
    <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/extensions/Buttons/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
        
        <?php include "include/navbar.php";?>   
        
        
        <div class="content">
            <div class="overlay"></div><!--for mobile view sidebar closing-->
            <!--title and breadcrumb-->
            <div class="top">
                <p class="page-title"><i class="fa fa-clipboard fa-fw"></i> Sales Report</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="#"></a>Report</li>
                   <!--  <li><a href="operations.php">Operations</a></li> -->
                    <li class="active">Sales Report</li>

                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                
             <div class="main-body">
              
               
                
                <!--list-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><i class="fa fa-list fa-fw"></i> Report List </div>
                    </div>
                    <div class="panel-body">
                        <table id="Service_list" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                         <p class="label-hr">Filters</p>
                            <div class="row">
                                
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>From Date</label>
                                             
                                        <input type="text" class="form-control" id="from_date" data-id="7" placeholder="Select date" value="<?=date('m-Y')?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>To Date</label>
                                             
                                        <input type="text" class="form-control" id="to_date" data-id="7" placeholder="Select date" value="<?=date('m-Y')?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>Payment Status</label>
                                        <select class="form-control" data-column="10">
                                            <option value="">All</option>
                                            <option value="Half">Partially Paid</option>
                                            <option value="Yes">Paid</option>
                                            <option value="No">Unpaid</option>
                                            <option value="C">Cancel</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                
                                 <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Bill Date</label>
                                         <input type="text" data-column="0" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Item Details</label>
                                         <input type="text" data-column="1" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Stock Infused</label>
                                         <input type="text" data-column="2" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Purchaser Name</label>
                                         <input type="text" data-column="3" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>

                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Purchaser Location</label>
                                         <input type="text" data-column="4" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Gross Cost Amount</label>
                                         <input type="text" data-column="6" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>GST Amount</label>
                                         <input type="text" data-column="7" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Net Amount</label>
                                         <input type="text" data-column="8" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>

                            </div>
                         <p class="label-hr">Report</p>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Details</th>
                                    <th>Stock Clearance (Quantity)</th>
                                    
                                    <th>Purchaser Name</th>
                                    <th>PurchaserLocation</th>
                                    <th>Paid</th>
                                    <th>Gross  Amount</th>
                                    <th>GST Amount</th>
                                    <th>Net Amount</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_bill where cancel='0'");
                                while($row=mysqli_fetch_object($select)){
                                $item=mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'");
                                $item_name='';
                                $item_qty='';
                                while($r=mysqli_fetch_object($item)){
                                     $r->qty;
                                     $item_name.=anything('subcategory','name',$r->item_id).' (QTY: '.$r->qty.')<br> ';
                                }
                                 $qty=mysqli_fetch_object(mysqli_query($link,"SELECT SUM(`qty`) as 'total' FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'"));
                                 $igst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*`qty`)*(`igst`/100)) as 'itotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'"));
                                 $cgst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*`qty`)*(`cgst`/100)) as 'ctotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'"));
                                 $sgst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*`qty`)*(`sgst`/100)) as 'stotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'"));
                                 $cost=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*`qty`)+((`bprice`*`qty`)*(`igst`/100))+((`bprice`*`qty`)*(`sgst`/100))+((`bprice`*`qty`)*(`cgst`/100))) as 'cost' FROM ".$prefix."purcheser_invoice WHERE `inv_id`='".$row->id."'"));

                                 $total_gst=$igst->itotal+$cgst->ctotal+$sgst->stotal;
                                 $nc=$cost->cost;
                                ?>
                                <tr>
                                    <td><?=$row->bdate?></td>
                                    <td><?=$item_name?></td>
                                    <td><?=$qty->total?></td>
                                    <td><?=anything('purchase','name',$row->pid)?></td>
                                    <td><?=anything('purchase','location',$row->pid)?></td>
                                    <td><?=InvoicePaid($row->id,'p')?></td>
                                    <td><?=number_format($nc-$total_gst,2,'.','')?></td>
                                    <td><?=number_format($total_gst,2,'.','')?></td>
                                    <td><?=InvoiceTotal($row->id,'p')?></td>
                                    <td><?=strtotime($row->bdate.' 00:00:00')?></td>
                                    <td><?=InvoicePaid($row->id,'p')=='Paid'?'Yes':(InvoicePaid($row->id,'p')=='Unpaid'?'No':(InvoicePaid($row->id,'p')=='Partially Paid'?'Half':'C'))?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
<?php include "include/footer.php";?>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="assets/plugins/DataTables/extensions/FileExport/jszip.min.js"></script>
<script type="text/javascript" src="assets/plugins/DataTables/extensions/FileExport/pdfmake.min.js"></script>
<script type="text/javascript" src="assets/plugins/DataTables/extensions/FileExport/vfs_fonts.js"></script>
<script type="text/javascript" src="assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="assets/js/common.js"></script>

<script type="text/javascript">
$('#from_date').datepicker({
    format:"mm-yyyy",
    minViewMode:1
});
$('#to_date').datepicker({
    format:"mm-yyyy",
    minViewMode:1
});
$('#Service_list').DataTable( {
    "columnDefs": [
    {"visible": false,"targets": 9},{"visible": false,"targets": 10}
     ],"order": [[ 0, 'desc' ]],
    'dom':"<'row'<'col-sm-6'B><'col-sm-3'l><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    "buttons": [
        // {'extend':'copy','exportOptions':{'columns':':visible'},'text':'<i class="fa fa-clipboard fa-fw"></i> Copy'},
        // {'filename':'Sales Report','extend':'csv','exportOptions':{'columns':':visible'},'text':'<i class="fa fa-file-text fa-fw"></i> CSV'},
        // {'filename':'Sales Report','extend':'excel','exportOptions':{'columns':':visible'},'text':'<i class="fa fa-file-excel-o fa-fw"></i> Excel'},
        // {'filename':'Sales Report','extend':'pdf','orientation':'landscape','pageSize':'A4','exportOptions':{'columns':':visible'},'text':'<i class="fa fa-file-pdf-o fa-fw"></i> PDF'},
        {'extend':'print','exportOptions':{'columns':':visible'},'text':'<i class="fa fa-print fa-fw"></i> Print',
        'customize':function(win){
            $(win.document.body).find('table').removeClass().css({
                'font-size': '12px',
                'border': '1px solid #888',
                'width':'100%'
            });
            $(win.document.body).find('table td, table th').removeClass().css({
                'padding': '3px',
                'border': '1px solid #888',
                'max-width':'24%'
            });
            $(win.document.body).find('table th').removeClass().css('background','#bbb');
            $(win.document.body).find('h1').remove();
            $(win.document.body).prepend('<hr>')
            $(win.document.body).prepend('<p><b>From:</b> '+$('#from_date').val()+' <b>To:</b> '+$('#to_date').val()+'</p>')
            $(win.document.body).prepend('<h3>Fire Check - '+$('#name').find('option:selected').text()+' Sales Report</h3>')

            $(win.document.body).append('<hr>')
            $(win.document.body).append('<p>TOTAL GROSS COST PRICE: <strong>INR '+calcTd(6)+'</strong></p>');
            $(win.document.body).append('<p>TOTAL GST PAID: <strong>INR '+calcTd(7)+'</strong></p>');
            $(win.document.body).append('<p>TOTAL NET COST PRICE: <strong>INR '+calcTd(8)+'</strong></p>');
        }}
    ]
});

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var d = ('01-'+$('#from_date').val()).split('-');
        var min = parseInt(new Date(d[1] + "-" + d[0] + "-" + d[2]).getTime())/1000;
        var d = ('01-'+$('#to_date').val()).split('-');
        var t = new Date(d[2], parseInt(d[1]), 0);

        var max = parseInt(t.getTime())/1000;
        var tstamp = parseFloat( data[9] ) || 0;
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && tstamp <= max ) ||
             ( min <= tstamp   && isNaN( max ) ) ||
             ( min <= tstamp   && tstamp <= max ) )
        {
            return true;
        }
        return false;
    }
);

$( '#name').on( 'change', function () {
    $('#Service_list').dataTable().fnFilter($(this).val(),$(this).data('id'));
});
$( '#from_date,#to_date').on( 'change', function () {
    $('#Service_list').DataTable().draw();
});  
function calcTd(id){
    var ttl = 0.00;
    $('#Service_list tbody').find('td:nth-child('+id+')').each(function(index, el) {
        ttl += parseFloat($(el).text());
    });
    return ttl.toFixed(2);
}

function filterColumn ( i , val) {
    $('#Service_list').DataTable().column( i ).search(val,false,true).draw();
}

$('[data-column]').on( 'keyup click change', function () {
    filterColumn( $(this).data('column'), $(this).val());
} );

$(document).ready(function() {
    $('#from_date,#to_date').trigger('change')
});
</script>
<?php if(isset($_GET)){?>
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