<?php 
ob_start();
include_once 'include/head.php';
if(isset($_GET['id'])){
    $b_id=$_GET['id'];
    $select=mysql_query("SELECT * FROM ".$prefix."seller_invoice WHERE `id`='".$b_id."'");
    $row=mysql_fetch_object($select);
    $list='';
    $gttl = 0.0;
    $gprice = 0.0;
    $gcgst = 0.0;
    $gsgst = 0.0;
    $gigst = 0.0;
    $gqty = 0.0;

    $s = mysql_query("SELECT * FROM ".$prefix."item_invoice WHERE `seller_bill` ='".$b_id."' ORDER BY `id`");
    while($r=mysql_fetch_object($s)){
        if(is_numeric($r->item_id)){
            $gqty += $r->stock;
            $price = floatval($r->price) * floatval($r->stock);
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
                <td align="center" width="11%"><b>'.$r->stock.' '.$r->unit.'</b></td>
                <td align="right" width="9%">'.number_format($r->price,2,'.',',').'</td>
                <td align="right" width="10%">'.number_format($price,2,'.',',').'</td>
                <td align="center" width="4%">'.$r->cgst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->sgst)/100),2,'.',',').'</td>
                <td align="center" width="4%">'.$r->sgst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->cgst)/100),2,'.',',').'</td>
                <td align="center" width="4%">'.$r->igst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->igst)/100),2,'.',',').'</td>
                <td align="right" width="10%"><b>'.number_format($tprice,2,'.',',').'</b></td>
            </tr>
            ';
        }else{
            $item_id = explode('|', $r->item_id);
            if($item_id[1]=='+'){
                $gqty += $r->stock;
                $price = floatval($r->price) * floatval($r->stock);
                $gprice += $price;
                $gcgst += ($price*(floatval($r->cgst)/100));
                $gsgst += ($price*(floatval($r->sgst)/100));
                $gigst += ($price*(floatval($r->igst)/100));
                $tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
                $gttl += $tprice;
            }else{
                $gqty += $r->stock;
                $price = floatval($r->price) * floatval($r->stock);
                $gprice -= $price;
                $gcgst -= ($price*(floatval($r->cgst)/100));
                $gsgst -= ($price*(floatval($r->sgst)/100));
                $gigst -= ($price*(floatval($r->igst)/100));
                $tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
                $gttl -= $tprice;
            }

            $list.='
            <tr nobr="true">
                <td width="19%" style="font-size:12px"><b>'.$item_id[0].'</b></td>
                <td align="right" width="8%">'.$r->hsn.'</td>
                <td align="center" width="11%"><b>'.$r->stock.' '.$r->unit.'</b></td>
                <td align="right" width="9%">'.number_format($r->price,2,'.',',').'</td>
                <td align="right" width="10%">'.$item_id[1].' '.number_format($price,2,'.',',').'</td>
                <td align="center" width="4%">'.$r->cgst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->sgst)/100),2,'.',',').'</td>
                <td align="center" width="4%">'.$r->sgst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->cgst)/100),2,'.',',').'</td>
                <td align="center" width="4%">'.$r->igst.'%</td>
                <td align="right" width="7%">'.number_format($price*(floatval($r->igst)/100),2,'.',',').'</td>
                <td align="right" width="10%"><b>'.$item_id[1].' '.number_format($tprice,2,'.',',').'</b></td>
            </tr>
            ';
        }
    }
    

    $ntl = round($gprice + $gcgst + $gsgst + $gigst);

}
    $replaceFoot = '<table cellspacing="0"  cellpadding="3" style="width:100%;border:1px solid #888; font-size:12px;">
            <tbody>
                <tr nobr="true">
                    <td colspan="2" style="font-size:12px" height="30">
                        <br>Total Amount (incl. tax) (in words): <b>'.numtowords(number_format($ntl,0,'.','')).'</b><br>
                    </td>
                </tr>

            </tbody>
        </table>
        <small  align="center" style="font-size:9px">*** This is a Computer Generated Invoice ***</small>';


    $footer = '<table cellspacing="0" border="0" cellpadding="3" style="width:100%; font-size:12px;">
            <tbody>
                <tr nobr="true">
                    <td colspan="2" style="font-size:12px" height="30">
                        <br>Continued...
                    </td>
                </tr>

            </tbody>
        </table>
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
        	$this->SetAutoPageBreak(false, 0);
    
		    $this->Image('./assets/image/water.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		    
		    $this->SetAutoPageBreak(true,30);
		    $this->setPageMark();

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
    $pdf->SetTitle('Purchase Invoice');
    $pdf->SetSubject('Parchase Invoice');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    $pdf->SetMargins(10,134,10);
    $pdf->setHeaderMargin(10);
    $pdf->setFooterMargin(30);
    $pdf->SetAutoPageBreak(true,30);
    
    $tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
    $pdf->setHtmlVSpace($tagvs);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    
    $header ='<p style="font-size:16px; text-align:center"><strong>Purchase Invoice</strong></p>
        <table cellspacing="0" cellpadding="3" style="width:100%;border:1px solid #888; font-size:15px;">
            <tbody height="220">
                <tr >
                    <td style="border: 1px solid #888;" rowspan="3" width="50%" height="210"><b>'.anything('seller','name',$row->seller_id).'</b>
                        <font style="font-size:12px">
                            <br>'.anything('seller','address',$row->seller_id).'
                            '.(anything('seller','phone',$row->seller_id)==''?'':'<br>Phone: '.anything('seller','phone',$row->seller_id)).'
                            '.(anything('seller','mobile',$row->seller_id)==''?'':'<br>Mobile: '.anything('seller','mobile',$row->seller_id)).'
                            '.(anything('seller','amobile',$row->seller_id)==''?'':'<br>Alt. Mobile: '.anything('seller','amobile',$row->seller_id)).'
                            '.(anything('seller','email',$row->seller_id)==''?'':'<br>Email: '.anything('seller','email',$row->seller_id)).'
                            <br>GSTIN: '.anything('seller','gstin',$row->seller_id).'
                            '.(anything('seller','cst',$row->seller_id)==''?'':'<br>CST: '.anything('seller','cst',$row->seller_id)).'
                            '.(anything('seller','pan',$row->seller_id)==''?'':'<br>PAN: '.anything('seller','pan',$row->seller_id)).'
                            '.(anything('seller','vat',$row->seller_id)==''?'':'<br>VAT: '.anything('seller','vat',$row->seller_id)).'
                        </font>
                        
                    </td>
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Invoice No. </small><br><p><b>'.$row->bill.'</b></p></td>
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Dated </small><br><p><b>'.(str_replace('____-__-__', '', $row->bdate)!=''?date('d-M-Y',strtotime($row->bdate)):'').'</b></p></td>
                </tr>
                <tr >
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Mode / Terms of Payment </small><br><p><b>'.$row->pterm.'</b></p></td>
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Other Ref </small><br><p><b>'.$row->oref.'</b></p></td>
                </tr>
                <tr >
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Buyer'."'".'s Order No. </small><br><p><b>'.$row->order_id.'</b></p></td>
                    <td style="border: 1px solid #888;" width="25%" height="70"><small>Date </small><br><p><b>'.(str_replace('____-__-__', '', $row->odate)!=''?date('d-M-Y',strtotime($row->odate)):'').'</b></p></td>
                </tr>
                <tr >
                    
                    <td style="border: 1px solid #888;" rowspan="3" width="50%" height="210"><small>Buyer</small><br><b>'.$site->name.'</b>
                        <font style="font-size:12px">
                            <br>'.$site->address.'
                            <br>Mobile: '.$site->phone.'
                            <br>Email: '.$site->email.'
                            <br>Website: '.$site->website.'
                            <br>GSTIN: '.$site->gst.'
                            <br>PAN: '.$site->pan.'
                           
                        </font>

                    </td>
                    <td style="border: 1px solid #888;" height="70"><small>Delivery Note </small><br><p><b>'.$row->delnote.'</b></p></td>
                    <td style="border: 1px solid #888;" height="70"><small>Delivery Date </small><br><p><b>'.(str_replace('____-__-__', '', $row->ddate)!=''?date('d-M-Y',strtotime($row->ddate)):'').'</b></p></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #888;" height="70"><small>Despatch Doc. No. </small><br><p><b>'.$row->desdoc.'</b></p></td>
                    <td style="border: 1px solid #888;" height="70"><small>Despatch Through </small><br><p><b>'.$row->desthr.'</b></p></td>
                </tr>
                <tr >
                    <td style="border: 1px solid #888;" height="70" colspan="2"><small>Terms of Delivery </small><br><b>'.$row->delterm.'</b></td>
                    
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
        
        <table cellspacing="0" cellpadding="2" style="width:100%;border:1px solid #888; font-size:11px;" >
            <thead>
                <tr nobr="true">
                    <th rowspan="2" width="19%">Description of Goods</th>
                    <th rowspan="2" width="8%">HSN/SAC</th>
                    <th rowspan="2" width="11%">QTY</th>
                    <th rowspan="2" width="9%">Rate/Unit<br>@<br><b>Rs.</b></th>
                    <th rowspan="2" width="10%">Total Price<br>(Taxable base price)<br><b>Rs.</b></th>
                    <th colspan="2" width="11%">Input SGST</th>
                    <th colspan="2" width="11%">Input CGST</th>
                    <th colspan="2" width="11%">Input IGST</th>
                    <th rowspan="2" width="10%">Total Amount<br>(incl. tax)<br><b>Rs.</b></th>
                </tr>
                <tr nobr="true">
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount<br><b>Rs.</b></th>
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount<br><b>Rs.</b></th>
                    <th width="4%">Rate<br>@</th>
                    <th width="7%">Amount<br><b>Rs.</b></th>
                </tr>
            </thead>

            <tbody>
                '.$list.'
            </tbody>
            <tfoot>
                <tr style="background-color: #eee;" nobr="true">
                    <td colspan="3"><strong>GRAND TOTAL (Rounded) (incl. tax)</strong></td>
                    <td></td>
                    <td align="right">'.number_format($gprice,2,'.',',').'</td>
                    <td></td>
                    <td align="right">'.number_format($gsgst,2,'.',',').'</td>
                    <td></td>
                    <td align="right">'.number_format($gcgst,2,'.',',').'</td>
                    <td align="right"></td>
                    <td align="right">'.number_format($gigst,2,'.',',').'</td>
                    <td align="right"><b>'.number_format($ntl,0,'.',',').'.00</b></td>
                </tr>
            </tfoot>
        </table>
        ';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
    ob_end_clean();
    $pdf->Output('Purchaser Invoice.pdf', 'I');
?>
