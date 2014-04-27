{block name="content"}
	<script src="{$jsScriptDir}categorySelect.js"></script>
    <script src="{$jsScriptDir}addCategory.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">Products</h3>
            	</div>
           		<div class="panel-body">
				{$product_row}
                <a class="btn btn-primary" href="./new_product.php">Add new product</a>
                </div>
            </div>   
        </div>
    </div>
{/block} 