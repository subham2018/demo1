<?php include "include/head.php";
if (isset($_GET['fdate'],$_GET['tdate'])) {
    $fdate = $_GET['fdate'];
    $tdate = $_GET['tdate'];
}else{
    $fdate = date('m-Y');
    $tdate = date('m-Y');
}
?>
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
                <p class="page-title"><i class="fa fa-clipboard fa-fw"></i> Consumer or purchaser Report</p>
                <ol class="breadcrumb">
                    <li><a href="#"></a>Report</li>
                   <!--  <li><a href="operations.php">Operations</a></li> -->
                    <li class="active">Consumer or purchaser Report</li>

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
                        
                         <p class="label-hr">Filters</p>
                            <div class="row">
                                <form method="GET" action="">
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>From Date</label>
                                             
                                        <input type="text" class="form-control" id="from_date" name="fdate" placeholder="Select date" value="<?=$fdate?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>To Date</label>
                                             
                                        <input type="text" class="form-control" id="to_date" name="tdate" placeholder="Select date" value="<?=$tdate?>">
                                    </div>
                                </div>
                                </form>
                                <div class="col-sm-4">      
                                    <div class="form-group text-center">
                                        <label>Business Secure Range</label>
                                             
                                        <select class="form-control" id="range">
                                            <option value="0-0">All</option>
                                            <option value="0-25000">0-25,000</option>
                                            <option value="25001-50000">25,001-50,000</option>
                                            <option value="50001-100000">50,001-1,00,000</option>
                                            <option value="100001-0">1,00,000+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Purchaser Name</label>
                                         <input type="text" data-column="0" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Purchaser ID</label>
                                         <input type="text" data-column="1" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Email</label>
                                         <input type="text" data-column="2" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Phone No.</label>
                                         <input type="text" data-column="3" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                

                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Consumer Location</label>
                                         <input type="text" data-column="5" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>GST No.</label>
                                         <input type="text" data-column="7" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>GST Paid</label>
                                         <input type="text" data-column="8" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                                <div class="col-sm-3">      
                                    <div class="form-group">
                                        <label>Net Cost Price</label>
                                         <input type="text" data-column="9" class="form-control" placeholder="Type Here">
                                    </div>
                                </div>
                            </div>
                         <p class="label-hr">Report</p>
                         <div class=" table-responsive">
                             <table id="Service_list" class="table table-hover table-bordered table-condensed" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Purchaser ID</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Location</th>
                                        <th>Amount</th>
                                        <th>GST No.</th>
                                        <th>GST Amount</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select=mysqli_query($link,"SELECT * FROM ".$prefix."purchase");
                                    while($row=mysqli_fetch_object($select)){
                                         $fdate_n  = explode('-',$fdate);
                                         $fdate_n = $fdate_n[1].'-'.$fdate_n[0].'-01';
                                         $tdate_n  = explode('-',$tdate);
                                         $tdate_n = $tdate_n[1].'-'.$tdate_n[0].'-01';
                                         $tdate_n = date('Y-m-t',strtotime($tdate_n));

                                        
                                        //Seller all gst
                                        $igst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*qty)*(`igst`/100)) as 'itotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id` in (SELECT `id` FROM ".$prefix."purcheser_bill WHERE `pid`='".$row->id."' AND `cancel`='0' AND (`bdate` between '".$fdate_n."' and '".$tdate_n."') ) "));

                                        $cgst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*qty)*(`cgst`/100)) as 'ctotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id` in (SELECT `id` FROM ".$prefix."purcheser_bill WHERE `pid`='".$row->id."' AND `cancel`='0' AND (`bdate` between '".$fdate_n."' and '".$tdate_n."') ) "));
                                        $sgst=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*qty)*(`sgst`/100)) as 'stotal' FROM ".$prefix."purcheser_invoice WHERE `inv_id` in (SELECT `id` FROM ".$prefix."purcheser_bill WHERE `pid`='".$row->id."' AND `cancel`='0' AND (`bdate` between '".$fdate_n."' and '".$tdate_n."') ) "));
                                        $cost=mysqli_fetch_object(mysqli_query($link,"SELECT SUM((`bprice`*`qty`)+((`bprice`*qty)*(`igst`/100))+((`bprice`*qty)*(`sgst`/100))+((`bprice`*qty)*(`cgst`/100))) as 'cost' FROM ".$prefix."purcheser_invoice WHERE `inv_id` in (SELECT `id` FROM ".$prefix."purcheser_bill WHERE `pid`='".$row->id."' AND `cancel`='0' AND (`bdate` between '".$fdate_n."' and '".$tdate_n."')) "));
                                        $total_gst=$igst->itotal+$cgst->ctotal+$sgst->stotal;
                                        $nc=$cost->cost!=''?$cost->cost:'0';

                                    ?>
                                    <tr>
                                        <td><?=$row->name?></td>
                                        <td><?=sprintf("%'07d", $row->id)?></td>
                                        <td><?=$row->email?></td>
                                        <td><?=$row->phone?></td>
                                        <td><?=$row->address?></td>
                                        <td><?=$row->location?></td>
                                        <td><?=$nc-$total_gst?></td>
                                        <td><?=$row->gstin?></td>
                                        <td><?=$total_gst?></td>
                                        <td><?=$nc?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
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
    "order": [[ 0, 'desc' ]],
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
            $(win.document.body).prepend('<h3>Fire Check - '+$('#name').find('option:selected').text()+' Consumer or purchaser Report</h3>')

            $(win.document.body).append('<p>TOTAL GROSS COST PRICE: <strong>INR '+calcTd(7)+'</strong></p>');
            $(win.document.body).append('<p>TOTAL GST PAID: <strong>INR '+calcTd(9)+'</strong></p>');
            $(win.document.body).append('<p>TOTAL NET COST PRICE: <strong>INR '+calcTd(10)+'</strong></p>');
        }}
    ]
});

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        
        // Business Secure Range

        var bmin = parseFloat($('#range').val().split('-')[0]);
        var bmax = parseFloat($('#range').val().split('-')[1]);
        var tamnt = parseFloat( data[9] ) || 0;

        if(bmin > 100000){
            if (tamnt >= bmin )
            {
                return true;
            }
            return false;
        }else if(bmax > 0)
            if(tamnt < bmax) $r = true;
            else $r=false;
        else $r=false;
    
        if ( ((tamnt >= bmin) && $r) || ((bmin==0) && (bmax==0)) )
        {
            return true;
        }
        return false;
    }
);

$( '#name').on( 'change', function () {
    $('#Service_list').dataTable().fnFilter($(this).val(),$(this).data('id'));
});
$( '#range').on( 'change', function () {
    $('#Service_list').DataTable().draw();
});  
$( '#from_date,#to_date').on( 'change input keypress', function () {
    $('form').submit();
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

$('input[data-column]').on( 'keyup click', function () {
    filterColumn( $(this).data('column'), $(this).val());
} );
</script>
</body>
</html>