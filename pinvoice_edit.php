<?php include_once 'include/head.php';
if(isset($_GET['id'])) $inv = row('purcheser_bill',$_GET['id']);
else header('location: purcheser_bill.php')
?>
<!doctype html>
<html>
<head>
<?php include_once 'include/header.php';?>
<title><?=$site->name?> Admin Panel</title>
<link rel="stylesheet" href="assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.standalone.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<style type="text/css">
    .table input,.table textarea,.table select{
        border:none;
        outline: none;
        width: 100%;
        resize: vertical;
    }
    .table select{width:85%;}
    .table .close{opacity: 1}
    .item{
        font-size: 12px;
        margin-top: 15px;
    }
    .item thead>tr:last-child{font-size: 10px;}
    .item thead>tr:first-child>th{border-bottom: none;}
    .item thead>tr:last-child>th{border-top: none;}
    .item th{text-align: center;}
    .item tfoot>tr>td,.item tbody>tr>td,.item tbody>tr>td>input{text-align: right;}
    .item tbody>tr>td:nth-child(3),.item tfoot>tr>td:nth-child(3),
    .item tbody>tr>td:nth-child(3)>input{text-align: center;}
    .item tfoot>tr>td:nth-child(1),.item tbody>tr>td:nth-child(1),.item tbody>tr>td:nth-child(1)>input,.item tbody>tr>td:nth-child(2),.item tbody>tr>td:nth-child(2)>input{text-align: left;}
    .item tbody>tr>td:nth-child(1)>input{width: 85%}
    .item tfoot .ttl{font-weight: bold;}
</style>
</head>

<body>
<?php include_once 'include/navbar.php';?>	
        <div class="content">
        	<div class="overlay"></div><!--for mobile view sidebar closing-->
        	<!--title and breadcrumb-->
            <div class="top">
            	<p class="page-title"><i class="fa fa-file-text-o fa-fw"></i> Sales Invoice Edit</p>
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="purcheser_bill.php">Purchaser Billing</a></li>
                    <li class="active">Sales Invoice Edit</li>
                </ol>
            </div>
            <!--main content start-->
            <div class="main-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-control" required id="pid">
                                        <option selected disabled value="">Select Purchaser</option>
                                        <?php

                                        $select=mysqli_query($link,"SELECT * FROM ".$prefix."purchase");
                                        while($row=mysqli_fetch_object($select)){
                                        
                                        ?>
                                        <option <?=$inv->pid==$row->id?'selected':''?> value="<?=$row->id?>"><?=$row->name?> [<?=$row->business?>]</option>
                                        <?php }?>
                                    </select>
                                </div>

                                <div class="form-control">
                                    <span id="pur">
                                        <p class="text-muted text-center">No purchaser selected!</p>
                                    </span>
                                </div>
                                <br>
                                <div class="form-control">
                                    <p><strong><?=$site->name?></strong><br>
                                    <?=$site->address?><br>
                                    Phone: <?=$site->phone?><br>
                                    Email: <?=$site->email?><br>
                                    Website: <?=$site->website?><br>
                                    GSTIN: <?=$site->gst?><br>
                                    CST: <?=$site->cst?><br>
                                    PAN: <?=$site->pan?><br>
                                    VAT: <?=$site->vat?><br></p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <strong>Invoice No.:</strong>
                                            <input type="text" data-name="bill" placeholder="Eg. 127/FC/17-18" value="<?=$inv->bill?>">
                                        </td>
                                        <td>
                                            <strong>Dated:</strong>
                                            <input type="text" data-mask="9999-99-99" data-name="bdate" placeholder="Eg. yyyy-mm-dd" value="<?=$inv->bdate?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Challan No.:</strong>
                                            <input type="text" data-name="challan" placeholder="Eg. xxxxxxxx" value="<?=$inv->challan?>">
                                        </td>
                                        <td>
                                            <strong>Dated:</strong>
                                            <input type="text" data-mask="9999-99-99" data-name="cdate" placeholder="Eg. yyyy-mm-dd" value="<?=$inv->cdate?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Buyer's Order No.:</strong>
                                            <input type="text" data-name="order_id" placeholder="Eg. xxxxxxxx" value="<?=$inv->order_id?>">
                                        </td>
                                        <td>
                                            <strong>Dated:</strong>
                                            <input type="text" data-mask="9999-99-99" data-name="odate" placeholder="Eg. yyyy-mm-dd" value="<?=$inv->odate?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Despatched Doc. No.:</strong>
                                            <input type="text" data-name="desdoc" placeholder="Eg. xxxxxxxx" value="<?=$inv->desdoc?>">
                                        </td>
                                        <td>
                                            <strong>Despatched Through:</strong>
                                            <input type="text" data-name="desthr" placeholder="Enter Details" value="<?=$inv->desthr?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Destination:</strong>
                                            <input type="text" data-name="dest" value="<?=$inv->dest?>" placeholder="Enter details">
                                        </td>
                                        <td>
                                            <strong>Delivery Note Date:</strong>
                                            <input type="text" data-name="delnote" data-mask="9999-99-99" value="<?=$inv->delnote?>" placeholder="Eg. yyyy-mm-dd">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <strong>Mode/Terms of Payment:</strong>
                                            <input type="text" data-name="mode" placeholder="Enter details" value="<?=$inv->mode?>" >
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <form action="purcheser_bill.php"  method="post">

                        	<input type="hidden" name="usubmit" value="submit">
                        	<input type="hidden" name="bill" value="" required>
                        	<input type="hidden" name="bdate" value="" required>
                        	<input type="hidden" name="challan" value="" required>
                        	<input type="hidden" name="cdate" value="" required>
                        	<input type="hidden" name="order_id" value="" required>
                        	<input type="hidden" name="odate" value="" required>
                        	<input type="hidden" name="desdoc" value="" required>
                        	<input type="hidden" name="desthr" value="" required>
                        	<input type="hidden" name="delnote" value="" required>
                            <input type="hidden" name="dest" value="" required>
                            <input type="hidden" name="mode" value="" required>
                        	<input type="hidden" name="pid" value="" required>
                            <input type="hidden" name="inv_id" value="<?=$inv->id?>">
	                        <!-- item table -->
	                        <table class="table table-bordered item">
	                            <thead>
	                                <tr>
	                                    <th >Description of Goods</th>
	                                    <th >HSN/SAC</th>
	                                    <th >QTY</th>
	                                    <th >Rate/Price</th>
	                                    <th >Total Price</th>
	                                    <th colspan="2">SGST</th>
	                                    <th colspan="2">CGST</th>
	                                    <th colspan="2">IGST</th>
	                                    <th >Total Amount</th>
	                                </tr>
	                                <tr>
	                                    <th></th>
	                                    <th></th>
	                                    <th></th>
	                                    <th></th>
	                                    <th>(Taxable base price)</th>
	                                    <th style="border-top:thin solid #ddd">Rate</th>
	                                    <th style="border-top:thin solid #ddd">Amount</th>
	                                    <th style="border-top:thin solid #ddd">Rate</th>
	                                    <th style="border-top:thin solid #ddd">Amount</th>
	                                    <th style="border-top:thin solid #ddd">Rate</th>
	                                    <th>Amount</th>
	                                    <th>(incl. tax)</th>
	                                </tr>
	                            </thead>

	                            <tbody>
                                <?php
                                $s = mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_invoice WHERE `inv_id` ='".$inv->id."'");
                                while($r=mysqli_fetch_object($s)){?>
                                    <tr>
                                        <td>    
                                            <?php if(is_numeric($r->item_id)){?>
                                            <select name="item[]" class="item-box">
                                                <option selected disabled value="">---Select Item---</option>
                                                <?php
                                                $select=mysqli_query($link,"SELECT * FROM ".$prefix."category");
                                                while($row=mysqli_fetch_object($select)){?>
                                                <optgroup  label="<?=$row->name?>">
                                                <?php
                                                    $select1=mysqli_query($link,"SELECT * FROM ".$prefix."subcategory WHERE `del`='0' AND `cat_id`='".$row->id."'");
                                                    while($row1=mysqli_fetch_object($select1)){
                                                    ?>
                                                    <option <?=$r->item_id==$row1->id?'selected':''?> value="<?=$row1->id?>"><?=$row1->name?></option>
                                                    <?php }?>
                                                </optgroup>
                                                <?php }?>
                                            </select>
                                            <input name="ptype[]" type="hidden" value=" ">
                                            <?php }else{ $item_id = explode('|',$r->item_id);?>
                                            <input name="item[]" type="text" value="<?=$item_id[0]?>">
                                            <input name="ptype[]" type="hidden" value="<?=$item_id[1]?>">
                                            <?php }?>
                                            <input type="hidden" value="<?=$r->id?>" name="iid[]">
                                            <button class="close"><i class="fa fa-trash fa-fw"></i></button>
                                            <?php if(!is_numeric($r->item_id)){?>
                                            <br><small class="text-muted">(<?=$item_id[1]?>)<?=$item_id[1]=='+'?'Add':'Minus'?> Type. This text will not be printed.</small>
                                            <?php }?>
                                        </td>
                                        <td><input name="hsn[]" type="text" value="<?=$r->hsn?>"></td>
                                        <td><input name="qty[]" step="0.01" min="0" type="number" value="<?=$r->qty?>"><br><input name="unit[]" type="text" maxlength="50" style='border-top:thin solid #ccc' placeholder="Unit" value="<?=$r->unit?>"></td>
                                        <td><input name="rate[]" step="0.01" min="0" type="number" value="<?=$r->bprice?>"></td>
                                        <td class="bttl">0.00</td>
                                        <td><input name="sgst[]" step="0.01" min="0" max="100" type="number" value="<?=$r->sgst?>"></td>
                                        <td class="stl">0.00</td>
                                        <td><input name="cgst[]" step="0.01" min="0" max="100" type="number" value="<?=$r->cgst?>"></td>
                                        <td class="ctl">0.00</td>
                                        <td><input name="igst[]" step="0.01" min="0" max="100" type="number" value="<?=$r->igst?>"></td>
                                        <td class="itl">0.00</td>
                                        <td class="ttl">0.00</td>
                                    </tr>
                                <?php }?>

	                                <tr>
	                                    <td>
                                            
	                                        <select name="item[]" class="item-box">
	                                            <option selected disabled value="">---Select Item---</option>
                                                <option value="p+">Add Particular (+)</option>
                                                <option value="p-">Add Particular (-)</option>
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
                                            <input name="ptype[]" type="hidden" value=" ">
                                            <input type="hidden" value="" name="iid[]">
	                                    </td>
	                                    <td><input name="hsn[]" type="text" value=""></td>
	                                    <td><input name="qty[]" step="0.01" min="0" type="number" value="0"><br><input name="unit[]" type="text" value="" maxlength="50" style='border-top:thin solid #ccc' placeholder="Unit"></td>
	                                    <td><input name="rate[]" step="0.01" min="0" type="number" value="0.00"></td>
	                                    <td class="bttl">0.00</td>
	                                    <td><input name="sgst[]" step="0.01" min="0" max="100" type="number" value="0"></td>
	                                    <td class="stl">0.00</td>
	                                    <td><input name="cgst[]" step="0.01" min="0" max="100" type="number" value="0"></td>
	                                    <td class="ctl">0.00</td>
	                                    <td><input name="igst[]" step="0.01" min="0" max="100" type="number" value="0"></td>
	                                    <td class="itl">0.00</td>
	                                    <td class="ttl">0.00</td>
	                                </tr>
	                            </tbody>
	                            <tfoot>
	                                <tr style="background-color: #eee">
	                                    <td><strong>GRAND TOTAL(Rounded) (incl. tax)</strong></td>
	                                    <td></td>
	                                    <td></td>
	                                    <td></td>
	                                    <td class="bttl">0.00</td>
	                                    <td></td>
	                                    <td class="stl">0.00</td>
	                                    <td></td>
	                                    <td class="ctl">0.00</td>
	                                    <td></td>
	                                    <td class="itl">0.00</td>
	                                    <td class="ttl">0.00</td>
	                                </tr>
	                            </tfoot>
	                        </table>
                            
	                        <!-- Amount in words -->
	                        <div class="row">
                                <div class="col-sm-12">
                                    <p>Total Amount (incl. tax) (in words): <strong id="inword"><?=numtowords(number_format(0,2,'.',''))?></strong></p>
                                </div>
                                    
                            </div>
	                        <!-- terms and sign -->
	                        <div class="row">
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Declaration / Terms</label>
	                                    <textarea name="term" class="form-control" id="dc" rows="5"><?=$inv->terms?></textarea>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Account Details</label>
	                                    <textarea name="account" class="form-control" id="ac" rows="5"><?=$inv->account?></textarea>
	                                </div>
	                            </div>
	                            <div class="col-sm-12 text-right">
                                    <a class="btn btn-success" target="new" href="sales_print.php?id=<?=$_GET['id']?>">Print</a> 
	                                <button class="btn btn-success">Save</button>
	                            </div>
	                        </div>
                        </form>

                    </div>
                </div>     
            </div>
            <!--main content end-->
        </div>
<?php include_once 'include/footer.php';?>
<script src="assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/plugins/ckeditor/ckeditor.js"></script>
<script src="assets/js/common.js"></script>
<script type="text/javascript">
CKEDITOR.replace('ac');
CKEDITOR.replace('dc');
    $(document).ready(function() {
        $('#pid,[data-name]').trigger('change');

        calc();
    });
	$('[data-name="bdate"],[data-name="odate"],[data-name="cdate"],[data-name="delnote"]').datepicker({format:'yyyy-mm-dd'});

      $('#pid').change(function(event) {
        id = $(this).val();
        $.ajax({
            type: 'post',
            url:'ajax/getPurchaser.php',
            data:{'id':id},
            success:function(data){
                data = JSON.parse(data);
                p='<small>Buyer</small>';
                p +='<p><strong>'+data[0]['name']+'</strong><br>';
                p +=''+data[0]['address']+'<br>';
                if(data[0]['phone']!='') p +='Phone: '+data[0]['phone']+'<br>';
                if(data[0]['mobile']!='') p +='Mobile: '+data[0]['mobile']+'<br>';
                if(data[0]['amobile']!='') p +='Alt. Mobile: '+data[0]['amobile']+'<br>';
                if(data[0]['email']!='') p +='Email: '+data[0]['email']+'<br>';
                p +='GSTIN: '+data[0]['gstin']+'<br>';
                if(data[0]['cst']!='') p +='CST: '+data[0]['cst']+'<br>';
                if(data[0]['pan']!='') p +='PAN: '+data[0]['pan']+'<br>';
                if(data[0]['vat']!='') p +='VAT: '+data[0]['vat']+'<br></p>';

                $('#pur').html(p);                
          
                $('[name="pid"]').val(id);
            }
        })
      });

      $('.item>tbody').on('change','.item-box',function(event) {
            c = $(this).closest('tr').clone();
            if($('.item-box').last().val() != null) $(this).closest('tbody').append($(c));

            $this = $(this);
            if(($this.val() != 'p+') && ($this.val() != 'p-')){
                $.ajax({
                    url: 'ajax/getItemPrice.php',
                    type: 'POST',
                    data: {id: $(this).val()},
                    success:function(data){
                        data = JSON.parse(data);
                        $($this.find('option')[1]).remove();
                        $($this.find('option')[1]).remove();
                        $this.closest('tr').find('[name="qty[]"]').attr('max', data[0]['stock']).val(1);
                        $this.closest('tr').find('[name="rate[]"]').val(data[0]['bp']);
                        $this.closest('tr').find('[name="unit[]"]').val(data[0]['unit']);
                        $this.closest('tr').find('[name="igst[]"]').val(data[0]['ig']);
                        $this.closest('tr').find('[name="cgst[]"]').val(data[0]['cg']);
                        $this.closest('tr').find('[name="sgst[]"]').val(data[0]['sg']);
                        $this.closest('td').find('.close').remove();
                        $this.closest('td').append('<button class="close"><i class="fa fa-trash fa-fw"></i></button>')
                        calc();
                    }
                });
            }
            else{
                if($this.val() == 'p+'){
                    $this.closest('td').html($('<input name="item[]" type="text" placeholder="Enter particulars (Add type)" value=""><input name="ptype[]" type="hidden" value="+"><input type="hidden" value="" name="iid[]"><button class="close"><i class="fa fa-trash fa-fw"></i></button><br><small class="text-muted">(+)Add Type. This info text will not be printed.</small>'));
                }
                else if($this.val() == 'p-'){
                    $this.closest('td').html($('<input name="item[]" type="text" placeholder="Enter particulars (Minus type)" value=""><input name="ptype[]" type="hidden" value="-"><input type="hidden" value="" name="iid[]"><button class="close"><i class="fa fa-trash fa-fw"></i></button><br><small class="text-muted">(-)Minus Type. This text will not be printed.</small>'));
                }
            }
      });
      

      $(document).on('click', '.close', function(event) {
        $(this).closest('tr').remove()
        calc();
      });

      $('.item>tbody').on('keyup change', '[name="qty[]"],[name="rate[]"],[name="igst[]"],[name="cgst[]"],[name="sgst[]"]', function(event) {
          calc();
      });

      function calc(){
        $('.item>tbody>tr').each(function(index, el) {
             if($(el).find('select[name="item[]"]').length == 1){
                if($(el).find('[name="item[]"]').val() != null){
                    qty = parseFloat($(el).find('[name="qty[]"]').val());
                    rate = parseFloat($(el).find('[name="rate[]"]').val());
                    igst = parseFloat($(el).find('[name="igst[]"]').val()) / 100.00;
                    sgst = parseFloat($(el).find('[name="sgst[]"]').val()) / 100.00;
                    cgst = parseFloat($(el).find('[name="cgst[]"]').val()) / 100.00;

                    bttl = qty*rate;
                    ttl = bttl + (bttl*cgst) + (bttl*sgst) + (bttl*igst)
                    $(el).find('.bttl').text(bttl.toFixed(2));
                    $(el).find('.ctl').text((bttl*cgst).toFixed(2));
                    $(el).find('.stl').text((bttl*sgst).toFixed(2));
                    $(el).find('.itl').text((bttl*igst).toFixed(2));

                    $(el).find('.ttl').text(ttl.toFixed(2))
                }
            }
            if($(el).find('input[name="item[]"]').length == 1){
                if($(el).find('[name="item[]"]').val().trim() != ''){
                    qty = parseFloat($(el).find('[name="qty[]"]').val());
                    rate = parseFloat($(el).find('[name="rate[]"]').val());
                    igst = parseFloat($(el).find('[name="igst[]"]').val()) / 100.00;
                    sgst = parseFloat($(el).find('[name="sgst[]"]').val()) / 100.00;
                    cgst = parseFloat($(el).find('[name="cgst[]"]').val()) / 100.00;

                    bttl = qty*rate;
                    ttl = bttl + (bttl*cgst) + (bttl*sgst) + (bttl*igst)
                    $(el).find('.bttl').text(bttl.toFixed(2));
                    $(el).find('.ctl').text((bttl*cgst).toFixed(2));
                    $(el).find('.stl').text((bttl*sgst).toFixed(2));
                    $(el).find('.itl').text((bttl*igst).toFixed(2));

                    if($(el).find('[name="ptype[]"]').val() == '-'){
                        $(el).find('.bttl').text('-'+bttl.toFixed(2));
                        $(el).find('.ttl').text('-'+ttl.toFixed(2))
                    }
                    else{
                        $(el).find('.bttl').text(bttl.toFixed(2));
                        $(el).find('.ttl').text(ttl.toFixed(2))
                    }
                }
            }
        });
        var q=0.00,b=0.00,c=0.00,s=0.00,i=0.00,t=0.00;

        $('.item>tbody [name="qty[]"]').each(function(index, el) {
            q += parseFloat($(el).val());
        });
        $('.item>tbody .bttl').each(function(index, el) {
            b += parseFloat($(el).text());
        });
        $('.item>tbody .ctl').each(function(index, el) {
            c += parseFloat($(el).text());
        });
        $('.item>tbody .stl').each(function(index, el) {
            s += parseFloat($(el).text());
        });
        $('.item>tbody .itl').each(function(index, el) {
            i += parseFloat($(el).text());
        });
        $('.item>tbody .ttl').each(function(index, el) {
            t += parseFloat($(el).text());
        });
        $('.item>tfoot .qty').text(q);
        $('.item>tfoot .bttl').text(b.toFixed(2));
        $('.item>tfoot .ctl').text(c.toFixed(2));
        $('.item>tfoot .stl').text(s.toFixed(2));
        $('.item>tfoot .itl').text(i.toFixed(2));
        $('.item>tfoot .ttl').text('Rs.'+t.toFixed(0));

         $.ajax({
            type: 'post',
            url:'ajax/getnumtotext.php',
            data:{'num':(b + c + s + i).toFixed(0)},
            success:function(data){
                $('#inword').text(data.trim())
            }
        });
      }


      $('[data-name]').on('change keyup', function(event) {
      	$('[name="'+$(this).data('name')+'"]').val($(this).val())
      });

      $('form').submit(function(event) {
          if($('[data-name="bill"]').val() == ''){
            $('[data-name="bill"]').focus()
            return false;
          }
          else if($('[data-name="challan"]').val() == ''){
            $('[data-name="challan"]').focus()
            return false;
          }
          else{
             $('.item>tbody>tr').each(function(index, el) {
                  if($(el).find('[name="item[]"]').val().trim() == "") $(el).remove();
            })
            return true;
         }
      });
</script>

</body>
</html>