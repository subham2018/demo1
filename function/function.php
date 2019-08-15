<?php
// site details
function site_info() {
    
    global $prefix,$link;
    
    $select = mysqli_fetch_object(mysqli_query($link,"SELECT * FROM `".$prefix."software_setting` WHERE id='1'"));
    return $select;
}
function software_info() {
    
    global $prefix,$link;
    
    $select = mysqli_fetch_object(mysqli_query($link,"SELECT * FROM `".$prefix."software_setting` WHERE id='1'"));
    return $select;
}
// site details
function home_info() {
    
    global $prefix,$link;
    
    $select = mysqli_fetch_object(mysqli_query($link,"SELECT * FROM `".$prefix."home_setting` WHERE id='1'"));
    return $select;
}


function row($table,$id) {
    
    global $prefix,$link;
    
    $select = mysqli_fetch_object(mysqli_query($link,"SELECT * FROM `".$prefix.$table."` WHERE id='".$id."'"));
    return $select;
}
function rowCondition($table,$field,$value) {
    
    global $prefix,$link;
    
    $select = mysqli_fetch_object(mysqli_query($link,"SELECT * FROM `".$prefix.$table."` WHERE `".$field."`='".$value."'"));
    return $select;
}


function variable_check($data)
{
  $data = is_array($data)?array_map("trim", $data):trim($data);
  $data = is_array($data)?array_map("stripslashes", $data):stripslashes($data);
  $data = is_array($data)?array_map("htmlspecialchars", $data):htmlspecialchars($data);
  // $data = is_array($data)?array_map("mysql_real_escape_string", $data):mysql_real_escape_string($data);
  return $data;
}

function variable_check_low($data)
{
  $data = is_array($data)?array_map("stripslashes", $data):stripslashes($data);
  $data = is_array($data)?array_map("htmlspecialchars", $data):htmlspecialchars($data);
  // $data = is_array($data)?array_map("mysql_real_escape_string", $data):mysql_real_escape_string($data);
  return $data;
}

function check_all_var_low() {
    
    foreach($_POST as $key=>$value)
        {
            $_POST[$key]=variable_check_low($value);
                          
        }
}

function un_set() {
    // unset variables
    foreach($_POST as $key=>$value)
        {
          unset($_POST[$key]);
              
        }
}

function check_all_var() {
    
    foreach($_POST as $key=>$value)
        {
            $_POST[$key]=variable_check($value);
                          
        }
}


// delete rows
function del($id,$table) {
    
    global $prefix,$link;
    
    $sql = mysqli_query($link,"DELETE FROM `".$prefix.$table."` WHERE id='".$id."'");
    return $sql;
    
}

// delete rows with condition
function del_condition($value,$table,$field) {
    
    global $prefix,$link;
    
    $sql = mysqli_query($link,"DELETE FROM `".$prefix.$table."` WHERE `".$field."`='".$value."'");
    return $sql;
    
}

function count_rows($table, $field, $value) {
    
    global $prefix,$link;
    
    $select = mysqli_query($link,"SELECT * FROM `".$prefix.$table."` WHERE ".$field."='".$value."'");
    return mysqli_num_rows($select);
    
}

function anything($table,$field,$id) {
    
    global $prefix,$link;
    
    $select = mysqli_query($link,"SELECT * FROM `".$prefix.$table."` WHERE id='".$id."'");
    $row = mysqli_fetch_object($select);
    if(mysqli_num_rows($select) == 1)
        return $row->$field;
    else return false;
    
}
function setData($table,$field,$data,$wfield,$wdata,$query=''){
    global $prefix,$link;
    
    if($query == '' && count_rows($table, $wfield, $wdata)> 0){
        if(mysqli_query($link,"UPDATE `".$prefix.$table."` SET `".$field."`='".$data."' WHERE `".$wfield."`='".$wdata."'"))
            return true;
        else return false;
    }
    else{
        if(mysqli_query($link,"INSERT INTO `".$prefix.$table."` SET ".$query))
            return true;
        else return false;
    }
}

function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality){
    if($image_width <= 0 || $image_height <= 0){return false;}
    if( $image_width > $image_height )
    {
        $y_offset = 0;
        $x_offset = ($image_width - $image_height) / 2;
        $s_size     = $image_width - ($x_offset * 2);
    }else{
        $x_offset = 0;
        $y_offset = ($image_height - $image_width) / 2;
        $s_size = $image_height - ($y_offset * 2);
    }
    $new_canvas = imagecreatetruecolor( $square_size, $square_size);
    imagealphablending( $new_canvas, false );
    imagesavealpha( $new_canvas, true );
    if(imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)){
        if(save_image($new_canvas, $destination, $image_type, $quality)) return true;
        else return false;
    }
}
function resize_image($source, $destination, $image_type, $minSize, $image_width, $image_height, $quality, $height_static, $width_static){
    if($height_static && $width_static) return false;
    else{
        if($minSize==0) {
            $minSize=$image_width;
            $height_static=false;
            $width_static=false;
        }
        $ratioW = $minSize / $image_width; 
        $ratioH = $minSize / $image_height;
        if($height_static) {$ratio = $ratioH;}
        elseif($width_static) {$ratio = $ratioW;}
        elseif($ratioW < $ratioH) {$ratio = $ratioW;}else{$ratio = $ratioH;}
        $newWidth = $image_width*$ratio;
        $newHeight = $image_height*$ratio;
        $dst_img = imagecreatetruecolor($newWidth,$newHeight);
        imagealphablending( $dst_img, false );
        imagesavealpha( $dst_img, true );

        if(imagecopyresampled($dst_img, $source, 0, 0, 0, 0, $newWidth, $newHeight, $image_width, $image_height)){
            if(save_image($dst_img, $destination, $image_type, $quality)) return true;
            else return false;
        }
    }
}
function save_image($source, $destination, $image_type, $quality){
    switch(strtolower($image_type)){
        case 'image/jpeg': case 'image/pjpeg': 
            imagejpeg($source, $destination, $quality); return true;
            break;
        case 'image/png': 
            $q=floor($quality / 10) - 1;
            imagepng($source, $destination, $q); return true;
            break;
        case 'image/gif': 
            imagegif($source, $destination, $quality); return true;
            break;
        default: return false;
    }
}


function ageCalculate($dob){
    if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    }else{
        return 0;
    }
}

function getDefImg($id){
    global $prefix;
    
    $select = mysqli_query($link,"SELECT `image` FROM `".$prefix."product_img` WHERE `default_img`='1' AND `pro_id`='".$id."'");
    $row = mysqli_fetch_object($select);
    if(mysqli_num_rows($select) == 1)
        return $row->image;
    else return false;
}

function moneyFormatIndia($num){
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

function getStock($id, $var){
    global $prefix;
    
    $select = mysqli_query($link,"SELECT `stock`,`varient` FROM `".$prefix."vendor_product` WHERE `id`='".$id."'");
    $row = mysqli_fetch_object($select);
    if($row->varient == '') return $row->stock;
    else{
        $select = mysqli_query($link,"SELECT `stock`,`varient` FROM `".$prefix."product_stock` WHERE `pro_id`='".$id."' AND `varient`='".$var."'");
        $row = mysqli_fetch_object($select);
        return $row->stock;
    }
}

function numtowords($num){ 
    $num = explode('.',$num);
    $no = $num[0];
   $point = substr($num[1],0,2);

   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? '' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);

  $decones = array( 
            1 => "One", 
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            );
$ones = array( 
            0 => "",
            1 => "One",     
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            ); 
$tens = array( 
            0 => "",
            2 => "Twenty", 
            3 => "Thirty", 
            4 => "Forty", 
            5 => "Fifty", 
            6 => "Sixty", 
            7 => "Seventy", 
            8 => "Eighty", 
            9 => "Ninety" 
            ); 
    $rettxt='';
  if($point){ 
        $rettxt = ""; 
        if($point < 20 && $point > 0){ 
            if(substr($point,0,1) == '0') $rettxt .= $decones[substr($point,1,1)]; 
            else $rettxt .= $decones[$point];
        }
        elseif($point < 100){ 
            $rettxt .= $tens[substr($point,0,1)]; 
            $rettxt .= " ".$ones[substr($point,1,1)]; 
        }
        
    } 
  if(trim($rettxt)!='')$rettxt = $rettxt." Paisa"; 

  if(trim($result)!='' && trim($rettxt)!='')  return $result . "Rupees and " . $rettxt." Only";
  elseif(trim($result)!='' && trim($rettxt)=='') return $result. "Rupees"." Only";
  elseif(trim($result)=='' && trim($rettxt)=='') return "Zero Rupees"." Only";
  else return $rettxt." Only";
}

function InvoiceTotal($id,$type){
    global $prefix,$link;

    $gttl = 0.0;
    $gprice = 0.0;
    $gcgst = 0.0;
    $gsgst = 0.0;
    $gigst = 0.0;
    $gqty = 0;

    if($type =='p'){
        $row = row('purcheser_bill',$id);
        $s = mysqli_query($link,"SELECT * FROM ".$prefix."purcheser_invoice WHERE `inv_id` ='".$id."'");
    }
    elseif($type =='s'){
        $row = row('seller_invoice',$id);
        $s = mysqli_query($link,"SELECT * FROM ".$prefix."item_invoice WHERE `seller_bill` ='".$id."'");
    }

    while($r=mysqli_fetch_object($s)){
        if($type =='p'){
            $gqty += $r->qty;
            $price = floatval($r->bprice) * floatval($r->qty);
        }elseif($type =='s'){
            $gqty += $r->stock;
            $price = floatval($r->price) * floatval($r->stock);
        }
        if(is_numeric($r->item_id)){
            $gprice += $price;
            $gcgst += ($price*(floatval($r->cgst)/100));
            $gsgst += ($price*(floatval($r->sgst)/100));
            $gigst += ($price*(floatval($r->igst)/100));
            $tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
            $gttl += $tprice;
        }
        else{
            $item_id = explode('|',$r->item_id);
            if($item_id[1]=='+'){
                $gprice += $price;
                $gcgst += ($price*(floatval($r->cgst)/100));
                $gsgst += ($price*(floatval($r->sgst)/100));
                $gigst += ($price*(floatval($r->igst)/100));
                $tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
                $gttl += $tprice;
            }else{
                $gprice -= $price;
                $gcgst -= ($price*(floatval($r->cgst)/100));
                $gsgst -= ($price*(floatval($r->sgst)/100));
                $gigst -= ($price*(floatval($r->igst)/100));
                $tprice = ($price*(floatval($r->cgst)/100)) + ($price*(floatval($r->sgst)/100)) + ($price*(floatval($r->igst)/100)) + $price;
                $gttl -= $tprice;
            }
        }
    }

    $ntl = round($gprice + $gcgst + $gsgst + $gigst);

    return $ntl;
}

function InvoicePaid($id,$type,$due=false){
    global $prefix,$link;
    $query = $type=='p'?" where `pb_id`='$id'":" where `sb_id`='$id'";
    $table = $type=='p'?"purcheser_bill":"seller_invoice";
    $ttl = InvoiceTotal($id,$type);
    $paid = mysqli_fetch_object(mysqli_query($link,"SELECT sum(`amount`) as 'amt' FROM ".$prefix."payment".$query." AND `del`='0'"));
    $cancel = anything($table,'cancel',$id);
    
    if($due){
        return $ttl - $paid->amt;
    }
    else{
        if($cancel == '1') return 'Cancel';
        elseif( intval($paid->amt) == 0) return 'Unpaid';
        elseif( (intval($paid->amt) > 0) AND (intval($paid->amt) < intval($ttl))) return 'Partially Paid';
        elseif( intval($paid->amt) == intval($ttl)) return 'Paid';
    }
    
}
?>

