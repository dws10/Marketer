{block name="css" append}
<link rel="stylesheet" type="text/css" href="../styles/main.css">
{/block}
{block name="topnav"}
<script type="text/javascript" src="../cont/js/client_logout.js"></script>
<nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Reburb-IT</a>
    </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="{$dashboardActive}"><a href="index.php">Dashboard</a></li>
            <li class="dropdown {$inventoryActive}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="./categories.php">Categories</a></li>
                    <li class="divider"></li>
                    <li><a href="./products.php">Products</a></li>
                </ul>
            </li>
            <li class="dropdown {$templatesActive}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Templates <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="shipping_templates.php">Shipping Templates</a></li>
                    <li class="divider"></li>
                    <li><a href="return_templates.php">Return Templates</a></li>
                </ul>
            </li>
            <li class="{$listingsActive}"><a href="./listings.php">Listings</a></li>
            <li class="{$shippingActive}"><a href="./orders.php">Shipping</a></li>
            <li class="{$supportActive}"><a href="#">Support</a></li>
            <li class="{$accountActive}"><a href="./account.php">Account</a></li>
        </ul>
        <form class="navbar-form navbar-right" role="search">
        	<label>Welcome {$username}</label>
            <button type="submit" class="btn btn-default logout">Logout</button>
        </form>
    </div><!-- /.navbar-collapse -->
</nav>
{/block}