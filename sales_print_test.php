<?php 
ob_start();
include_once 'include/head.php';
if(isset($_GET['id'])){
	$b_id=$_GET['id'];
	$select=mysql_query("SELECT * FROM ".$prefix."purcheser_bill WHERE `id`='".$b_id."'");
	$row=mysql_fetch_object($select);
	$list='';
	$gttl = 0.0;
	$gprice = 0.0;
	$gcgst = 0.0;
	$gsgst = 0.0;
	$gigst = 0.0;
	$gqty = 0;

	$s = mysql_query("SELECT * FROM ".$prefix."purcheser_invoice WHERE `inv_id` ='".$b_id."'");
    while($r=mysql_fetch_object($s)){
    	$gqty += $r->qty;
    	$price = floatval($r->bprice) * floatval($r->qty);
    	$gprice += $price;
    	$gcgst += ($price*(floatval($r->cgst)/100));
    	$gsgst += ($price*(floatval($r->sgst)/100));
    	$gigst += ($price*(floatval($r->igst)/100));
    	$tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
    	$gttl += $tprice;

    	$list.='
    	<tr nobr="true">
	        <td width="19%" style="font-size:12px"><b>'.str_replace('CO2','CO<sub>2</sub>',str_replace('co2','CO<sub>2</sub>',str_replace('Co2','CO<sub>2</sub>',anything('subcategory','name',$r->item_id)))).'</b></td>
	        <td align="right" width="8%">'.$r->hsn.'</td>
	        <td align="center" width="11%"><b>'.$r->qty.' '.$r->unit.'</b></td>
	        <td align="right" width="9%">'.number_format($r->bprice,2,'.',',').'</td>
	        <td align="right" width="10%">'.number_format($price,2,'.',',').'</td>
	        <td align="center" width="4%">'.$r->cgst.'%</td>
	        <td align="right" width="7%">'.number_format($price*(floatval($r->cgst)/100),2,'.',',').'</td>
	        <td align="center" width="4%">'.$r->sgst.'%</td>
	        <td align="right" width="7%">'.number_format($price*(floatval($r->sgst)/100),2,'.',',').'</td>
	        <td align="center" width="4%">'.$r->igst.'%</td>
	        <td align="right" width="7%">'.number_format($price*(floatval($r->igst)/100),2,'.',',').'</td>
	        <td align="right" width="10%"><b>'.number_format($tprice,2,'.',',').'</b></td>
	    </tr>
    	';
    }

    $plist ='';
    $ppr = 0.00;
    $ptype = explode('|',$row->ptype);
    $per = explode('|',$row->per);
    $pamnt = explode('|',$row->pamnt);

    foreach ($ptype as $key => $value) {
    	$plist .='
    	<tr nobr="true">
    		<td width="60%">'.$per[$key].'</td>
            <td width="40%" align="right">('.$value.') '.$pamnt[$key].'</td>
    	</tr>';

    	if($value == '+') $ppr += floatval($pamnt[$key]);
    	elseif($value == '-') $ppr -= floatval($pamnt[$key]);
    }

    $ntl = round($gprice + $ppr + $gcgst + $gsgst + $gigst);
}
	$replaceFoot = '<table cellspacing="0" cellpadding="3" style="width:100%;border:1px solid #888; font-size:12px;">
			<tbody>
				<tr>
					<td colspan="2" style="font-size:12px" height="30">
						<br>Total Amount (incl. tax) (in words): <b>'.numtowords(number_format($ntl,2,'.','')).'</b>
					</td>
				</tr>
				<tr >
					<td style="border: 1px solid #888;" height="105"><b>Declaration / Terms</b><br>'.htmlspecialchars_decode($row->terms).'
					</td>
					<td style="border: 1px solid #888;" height="105"><b>Account Details</b><br>'.htmlspecialchars_decode($row->account).'
					</td>
				</tr>
				<tr>	
					<td style="border: 1px solid #888;" height="100"><p>Customer`s Seal and Signature</p></td>
					<td style="border: 1px solid #888;" height="100"><h3 align="right"><b>'.$site->name.'</b></h3><p></p><p></p><p></p><p align="right">Authorised Signatory</p></td>
				</tr>

			</tbody>
		</table>
		<b  align="center" style="font-size:12px;">All Disputes Under Kolkata Jurisdiction Only</b><br>
		<small  align="center" style="font-size:9px">*** This is a Computer Generated Invoice ***</small>';


	$footer = '<table cellspacing="0" border="0" cellpadding="3" style="width:100%; font-size:12px;">
			<tbody>
				<tr>
					<td colspan="2" style="font-size:12px" height="30">
						<br>Continued...
					</td>
				</tr>
				<tr >
					<td height="105">
						<span id="des"></span>
					</td>
					<td height="105">
						<span id="acc"></span>
					</td>
				</tr>
				<tr>	
					<td height="100"><span id="sig"></span></td>
					<td height="100"><span id="stamp"></span></td>
				</tr>

			</tbody>
		</table>
		<b  align="center" style="font-size:12px;">All Disputes Under Kolkata Jurisdiction Only</b><br>
		<small  align="center" style="font-size:9px">*** This is a Computer Generated Invoice ***</small>';

	require_once 'assets/plugins/TCPDF-master/config/tcpdf_config.php';
	require_once 'assets/plugins/TCPDF-master/tcpdf.php';
	class MYPDF extends TCPDF {
		public $isLastPage = false;

		public function lastPage($resetmargins=false) {
		    $this->setPage($this->getNumPages(), $resetmargins);
		    $this->isLastPage = true;
		}

		public function Header() {
		    $headerData = $this->getHeaderData();
		    $this->SetFont('helvetica', 'N', 10);
		    $this->writeHTML($headerData['string']);
		}
		public function Footer() {
			global $footer,$replaceFoot;

		    $this->SetFont('helvetica', 'N', 10);
		    if(!$this->isLastPage){
		    	$this->writeHTML($footer);
		    }
		    else{
		    	$this->writeHTML($replaceFoot);
		    }
		}
	}
	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator('Firechek India');
	$pdf->SetAuthor('Firechek India');
	$pdf->SetTitle('Tax Invoice');
	$pdf->SetSubject('Tax Invoice');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
	$pdf->SetMargins(10,134,10);
	$pdf->setHeaderMargin(10);
	$pdf->setFooterMargin(95);
	$pdf->SetAutoPageBreak(true,95);
	$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
	$pdf->setHtmlVSpace($tagvs);

	//$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	$header ='<p style="font-size:16px; text-align:center"><strong>Tax Invoice</strong></p>
		<table cellspacing="0" cellpadding="3" style="width:100%;border:1px solid #888; font-size:16px;">
			<tbody height="220">
				<tr >
					<td style="border: 1px solid #888;" rowspan="3" width="50%" height="210">
					
						<b>'.$site->name.'</b>
						<font style="font-size:13px">
							<br>'.$site->address.'
							<br>Mobile: '.$site->phone.'
							<br>Email: '.$site->email.'
							<br>Website: '.$site->website.'
							<br>GSTIN: '.$site->gst.'
							<br>'.($site->cst==''?'':'CST: '.$site->cst).'
							<br>PAN: '.$site->pan.'
							<br>'.($site->vat==''?'':'VAT: '.$site->vat).'
						</font>
					</td>
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Invoice No. </small><br><p><b>'.$row->bill.'</b></p></td>
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Dated </small><br><p><b>'.date('d-M-Y',strtotime($row->bdate)).'</b></p></td>
				</tr>
				<tr >
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Challan No. </small><br><p><b>'.$row->challan.'</b></p></td>
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Dated </small><br><p><b>'.date('d-M-Y',strtotime($row->cdate)).'</b></p></td>
				</tr>
				<tr >
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Buyer`s Order No. </small><br><p><b>'.$row->order_id.'</b></p></td>
					<td style="border: 1px solid #888;" width="25%" height="70"><small>Dated </small><br><p><b>'.date('d-M-Y',strtotime($row->odate)).'</b></p></td>
				</tr>
				<tr >
					
					<td style="border: 1px solid #888;" rowspan="3" width="50%" height="210"><small>Buyer</small><br>
					
						<b>'.anything('purchase','name',$row->pid).'</b>
						<font style="font-size:13px">
							<br>'.anything('purchase','address',$row->pid).'
							'.($row->pphone==''?'':'<br>Phone: '.$row->pphone).'
							'.(anything('purchase','mobile',$row->pid)==''?'':'<br>Mobile: '.anything('purchase','mobile',$row->pid)).'
							'.(anything('purchase','amobile',$row->pid)==''?'':'<br>Alt. Mobile: '.anything('purchase','amobile',$row->pid)).'
							'.($row->pemail==''?'':'<br>Email: '.$row->pemail).'
							<br>GSTIN: '.anything('purchase','gstin',$row->pid).'
							'.(anything('purchase','cst',$row->pid)==''?'':'<br>CST: '.anything('purchase','cst',$row->pid)).'
							'.(anything('purchase','pan',$row->pid)==''?'':'<br>PAN: '.anything('purchase','pan',$row->pid)).'
							'.(anything('purchase','vat',$row->pid)==''?'':'<br>VAT: '.anything('purchase','vat',$row->pid)).'
						</font>
					</td>
					<td style="border: 1px solid #888;" height="70"><small>Despatched Doc. No. </small><br><p><b>'.$row->desdoc.'</b></p></td>
					<td style="border: 1px solid #888;" height="70"><small>Despatched Through </small><br><p><b>'.$row->desthr.'</b></p></td>
				</tr>
				<tr >
					<td style="border: 1px solid #888;" height="70"><small>Destination </small><br><p><b>'.$row->dest.'</b></p></td>
					<td style="border: 1px solid #888;" height="70"><small>Delivery Note Date </small><br><b>'.$row->delnote.'</b></td>
					
				</tr>
				<tr >
					<td style="border: 1px solid #888;" colspan="2" height="70"><small>Mode/Terms of Payment </small><br><p><b>'.$row->mode.'</b></p></td>
					
				</tr>

			</tbody>
				
		</table>';
	$pdf->setHeaderData($ln='', $lw=0, $ht='', $header, $tc=array(0,0,0), $lc=array(0,0,0));
	$pdf->AddPage();

	$html = '<style>
		.address{font-size:10px; float:right;}
		.t1{text-align:right;border-right:1px solid #888;}
		.t2{width:200px;}
		td{vertical-align: top;border: 1px solid #888;}
		th{border: 1px solid #888; text-align:center; font-size:10px}
		small{font-size:12px;}

	</style>
		
		
		<!-- item table -->
        <table cellspacing="0" cellpadding="2" style="width:100%;border:1px solid #888; font-size:11px;" >
            <thead>
                <tr nobr="true">
                    <th rowspan="2" width="19%">Description of Goods</th>
                    <th rowspan="2" width="8%">HSN/SAC</th>
                    <th rowspan="2" width="11%">QTY</th>
                    <th rowspan="2" width="9%">Rate/Unit<br>@</th>
                    <th rowspan="2" width="10%">Total Price<br>(Taxable base price)</th>
                    <th colspan="2" width="11%">Output CGST</th>
                    <th colspan="2" width="11%">Output SGST</th>
                    <th colspan="2" width="11%">Output IGST</th>
                    <th rowspan="2" width="10%">Total Amount<br>(incl. tax)</th>
                </tr>
                <tr nobr="true">
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount</th>
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount</th>
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount</th>
                </tr>
            </thead>

            <tbody>
                '.$list.'
            </tbody>
            <tfoot>
                <tr style="background-color: #eee;" nobr="true">
                    <td><strong>GRAND TOTAL</strong></td>
                    <td></td>
                    <td align="center"><b></b></td>
                    <td></td>
                    <td align="right">'.number_format($gprice,2,'.',',').'</td>
                    <td></td>
                    <td align="right">'.number_format($gcgst,2,'.',',').'</td>
                    <td></td>
                    <td align="right">'.number_format($gsgst,2,'.',',').'</td>
                    <td align="right"></td>
                    <td align="right">'.number_format($gigst,2,'.',',').'</td>
                    <td align="right"><b>'.number_format($gttl,2,'.',',').'</b></td>
                </tr>
            </tfoot>
        </table>
        <h2></h2>
        <table cellspacing="0" cellpadding="2" style="width:100%;border:1px solid #888; font-size:11px;" >
            <thead>
                <tr nobr="true" style="font-size:12px">
                    <th width="60%" align="left"><strong>Particulars</strong></th>
                    <th width="40%" align="right"><strong>Amount</strong></th>
                </tr>
            </thead>

            <tbody>
                '.$plist.'
            </tbody>
        </table>
        <table cellspacing="0" cellpadding="2" style="width:100%;border:1px solid #888; font-size:14px;" >
            <tbody>
                <tr style="background-color: #eee;" nobr="true">
                    <td><strong>NET TOTAL (Rounded) (incl. tax)</strong></td>
                    <td align="right"><b>INR '.number_format($ntl,2,'.',',').'</b></td>
                </tr>
            </tbody>
        </table>
        ';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->lastPage();
	ob_end_clean();
	$pdf->Output('Tax_invoice'.$_GET['id'].'.pdf', 'I');
?>
