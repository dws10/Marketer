{block name="topnav"}
<script type="text/javascript" src="./cont/js/client_login.js"></script>
<link rel="stylesheet" type="text/css" href="./styles/main.css">
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
            <li class="{$homeActive}"><a href="./index.php">Homepage</a></li>
            <li class="{$featuresActive}"><a href="#">Features</a></li>
            <li class="{$paymentActive}"><a href="#">Payment</a></li>
            <li class="{$contactActive}"><a href="#">Contact</a></li>
            <li class="{$registerActive}"><a href="./register.php">Register</a></li>
        </ul>
        <form id="login" name="login" class="navbar-form navbar-right" role="search">
        
        
        	
            <div class="form-group">
            	<input type="text" name="email" class="form-control" placeholder="Enter Your Email">
            </div>
            <div class="form-group">
            	<input type="password" name="password" class="form-control" placeholder="Enter Your Password">
            </div>
            <button type="submit" class="btn btn-default login">Login</button>
            <p>Forgot your password? Reset it <a href="">here</a></p>
        </form>
        <div id="login_error" class="navbar-right"></div>
       
    </div><!-- /.navbar-collapse -->
</nav>
{/block}