<div class="topbar">
    <div class="logo"><a href="index.php"><img src="<?=UPLOAD_IMG_PATH.$site->logo?>" alt="..."></a></div>
    <button class="toggle-btn"><i class="fa fa-bars fa-fw"></i></button>
    <ul class="right-menu">
        
        <!-- <li><a href="#"><img src="assets/image/usr.png" alt="..."><?=$user->name?> <i class="fa fa-angle-down fa-fw"></i></a></li> -->
        <li><a href="logout.php"><i class="fa fa-power-off fa-lg fa-fw"></i> Logout</a></li>
    </ul>
</div>
<div class="main">
    <!--sidebar start-->
    <div class="sidebar">
        <div class="user-details">
            <p class="name clearfix">
                <img src="assets/image/usr.png" alt="...">
                <span>Welcome</span>
                <span><?=$user->name?></span><br>
                <small><?=$user_type=='WH'?'(Warehouse)':''?></small><br>
                <small><a href="change-password.php" style="color:white">Change Login Settings</a></small>
            </p>
        </div>
        <span class="menu-divider">Menu Navigation</span>
        <ul class="menu-item">
            <li><a href="index.php"><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a></li>
            <?php if($user_type=='Admin'){?>
            
            <li><a href="seller.php"><i class="fa fa-users fa-fw"></i> <span>Seller</span></a></li>
            <li><a href="purchaser.php"><i class="fa fa-cart-arrow-down fa-fw"></i> <span>Purchaser</span></a></li>
             <li class="has-sub">
                <a href="#"><i class="fa fa-cube fa-fw"></i> <span>Product</span></a>
                <ul>
                    <li><a href="category.php">Category</a></li>
                    

                </ul>
            </li>
            <li><a href="stock_manage.php"><i class="fa fa-cubes fa-fw"></i> <span>Stock Manage</span></a></li>
            <?php }?>
            <li><a href="stock_transfer.php"><i class="fa fa-truck"></i><span>Stock Transfer</span></a></li>
            <?php if($user_type=='WH'){?>
            <li><a href="warehouse_stock.php"><i class="fa fa-cubes"></i> <span>Warehouse Stock</span></a></li>
            <?php } ?>
            <?php if($user_type=='Admin'){?>
            <li><a href="warehouse_manage.php"><i class="fa fa-home fa-fw"></i> <span>Warehouse Manage</span></a></li>
            <li><a href="selling_invoice.php"><i class="fa fa-pencil-square-o fa-fw"></i> <span>Seller Billing</span></a></li>
            <li><a href="purcheser_bill.php"><i class="fa fa-inr fa-fw"></i> <span>Purchaser Billing</span></a></li>
             <li><a href="proforma_bill.php"><i class="fa fa-inr fa-fw"></i> <span>Purchaser Proforma</span></a></li>


            <li class="has-sub">
                <a href="#"><i class="fa fa-clipboard fa-fw"></i> <span>Report</span></a>
                <ul>
                    <li><a href="purchase_report.php">Purchase Report</a></li>
                    <li><a href="sale_report.php">Sales Report</a></li>
                    <li><a href="vendor_report.php">Seller or Vendor Report</a></li>
                    <li><a href="consumer_report.php">Purchaser or Consumer Report</a></li>
                    <li><a href="stock_report.php">Stock Report</a>
                    <li><a href="gst_in_report.php">GST OUTPUT Report</a>
                    <li><a href="gst_out_report.php">GST INPUT Report</a>
                </ul>
            </li>
            <li><a href="certificate.php"><i class="fa fa-pencil-square-o  fa-fw"></i> <span>Certificate Module</span></a></li>
            <li><a href="software_settings.php"><i class="fa fa-cogs fa-fw"></i> <span>Software Settings</span></a></li>
           

            <?php }?>
        </ul>
       
    </div>
    <!--sidebar end-->