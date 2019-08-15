<?php 
ob_start();
include_once 'include/head.php';
if(isset($_GET['id'])){
    $cer = row('certificate',$_GET['id']);
}
    require_once 'assets/plugins/TCPDF-master/config/tcpdf_config.php';
    require_once 'assets/plugins/TCPDF-master/tcpdf.php';
    class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            // get the current page break margin
            $bMargin = $this->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            // disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            // set bacground image
            if(isset($_REQUEST['p']))
                $this->Image('assets/image/cer.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $this->setPageMark();
        }
    }
    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator('Firecheck India');
    $pdf->SetAuthor('Firecheck India');
    $pdf->SetTitle('Firecheck Certificate');
    $pdf->SetSubject('Firecheck Certificate');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    $pdf->SetMargins(0,0,0);
    $pdf->SetAutoPageBreak(TRUE, 0);
    $pdf->setFooterMargin(0);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);

    $pdf->AddPage();


    $pdf->writeHTMLCell(100, 10, 12, 10, '<small>Certificate ID: '.sprintf('%09d',$cer->id).'</small>', 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(45, 10, 165, 10, '<small>Company ID: '.sprintf('%09d',$cer->compid).'</small>', 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(130, 5, 25, 75, '<b>'.$cer->name.'</b>', 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(100, 5, 25, 82, $cer->address1, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(100, 5, 25, 89, $cer->address2, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(100, 5, 25, 95, $cer->address3, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(45, 5, 160, 89, '<b> <font size="+1">'.$cer->date.'</font></b>', 0, 0, 0, true, '', true);

    $pdf->writeHTMLCell(130, 5, 60, 121, '<b> <font size="+1">'.$cer->name.'</font></b>', 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(163, 5, 35, 131, $cer->address1, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(170, 5, 35, 141, $cer->address2, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(80, 5, 35, 151, $cer->address3, 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(55, 5, 135, 151, '<b> <font size="+1">'.$cer->odate.'</font></b>', 0, 0, 0, true, '', true);

    $pdf->writeHTMLCell(180, 78, 15, 163, htmlspecialchars_decode($cer->dsc), 0, 0, 0, true, '', true);

    $pdf->writeHTMLCell(50, 5, 74, 256, '<b><div style="background-color:#7ed6df">'.$cer->pdate.'</div></b>', 0, 0, 0, true, '', true);
    $pdf->writeHTMLCell(50, 5, 74, 266, '<b><div style="background-color:yellow">'.$cer->cdate.'</div></b>', 0, 0, 0, true, '', true);

    $pdf->SetFont('zapfdingbats', '', 14);

    if($cer->type=='New')
        $pdf->writeHTMLCell(10, 10, 165, 108, '3', 0, 0, 0, true, '', true);
    elseif($cer->type=='Refill') $pdf->writeHTMLCell(10, 10, 183, 108, '3', 0, 0, 0, true, '', true);
    else{
        $pdf->writeHTMLCell(10, 10, 165, 108, '3', 0, 0, 0, true, '', true);
        $pdf->writeHTMLCell(10, 10, 183, 108, '3', 0, 0, 0, true, '', true);
    }
    $pdf->lastPage();
    ob_end_clean();
    $pdf->Output('Certificate-'.date('d-m-Y').'.pdf', 'I');
?>
