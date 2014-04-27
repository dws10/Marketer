
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

{$xajax_js}

{block name="css"}{/block}


<!-- jQuery setup-->
<script type="text/javascript" src="{$libraryDir}jquery-1.10.2-min/jquery-1.10.2-min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<!--jQuery same height fix-->
<script type="text/javascript" src="{$jsScriptDir}sameHeightByClass.js"></script>
<script>
	//onload run same height fixes
	$(document).ready(function(){
		sameHeightByClass('top-articles');
	});
	//call same height fixs when window is resized
	$(window).resize(function(){
		//remove height attribute to reset box heights
		$('.top-articles').height('');
		//reset all heights to new maxHeight
		sameHeightByClass('top-articles');
	});
</script>

<title>{$title}</title>

<!-- Bootstrap Setup-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="row">
        <div class="col-md-12">
            {block name="topnav"}{/block}
        </div>
    </div>
    {block name="banner"}{/block}
    {block name="content"}{/block}   

</body>

</html> 