{block name="css" append}
	<!-- Generic page styles -->
    <link rel="stylesheet" href="../blueimp-file-upload-9.5.6/css/style.css">
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="../blueimp-file-upload-9.5.6/css/jquery.fileupload.css">
    <link rel="stylesheet" href="../blueimp-file-upload-9.5.6/css/jquery.fileupload-ui.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="../blueimp-file-upload-9.5.6/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="../blueimp-file-upload-9.5.6/css/jquery.fileupload-ui-noscript.css"></noscript>
{/block}

{block name="content"}

    <script src="../cont/js/add_product.js"></script>
    <script src="../cont/js/editProduct.js"></script>
   
    <script src="../cont/js/upload-image.js"></script>
    <script src="../cont/js/imageSort.js"></script>
    <script src="../cont/js/deleteImage.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Product</h3>
            	</div>
           		<div class="panel-body">
                	<form id="edit_general">           
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">General Attributes</h3>
                                    </div>
                                    <div class="panel-body">
                                        	<input type="hidden" name="product_id" value="{$product_id}"/>
                                            <div class="form-group">
                                                <div class="input-group">
                                                  <label class="input-group-addon">Product Category</label>
                                                  <select class="form-control product_category" name="category">
                                                    {$categories}
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                  <label class="input-group-addon">Product Title</label>
                                                  <input type="text" class="form-control" value="{$product_title}" name="title" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                  <label class="input-group-addon">Product Description</label>
                                                  <textarea class="form-control" name="description" rows="10">{$product_description}</textarea>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block edit_general">Confirm General Changes</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block cancel_edit">Cancel General Changes</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                    <form id="edit_attributes">
                    	<input type="hidden" name="product_id" value="{$product_id}"/>
                    	<div id="custom-attribute-row" class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <div class="panel panel-info top-articles">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Ebay Recommended</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul id="recommendation_btns" class="list-group">
                                           {$specific_btns}
                                            
                                        </ul>
                                        {$specific_btns_script}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-info top-articles">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Custom Attributes</h3>
                                    </div>
                                    <div class="panel-body">
                                        
                                            <ul id="attribute-list" class="list-group attribute-list">
                                               {$attribute_inputs}
                                                
                                            </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                         <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                  <button class="btn btn-primary btn-block edit_attributes">Confirm Attributes Changes</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block cancel_edit">Cancel Attributes Changes</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                             <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Product Gallery</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <form id="fileupload" action="../cont/php/upload_image.php" target="upload_iframe" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="product_id" value="{$product_id}"/>
                                            <div class="col-md-2">
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Upload</span>
                                                    <input type="file" name="image" id="image" class="upload" onchange="startUpload();" />
                                                </div>
                                            </div>  
                                            <div class="col-md-1">                           
                                                <img id="loading" src="../images/loading.gif" alt="loading..." width="30" height="30"/>
                                            </div>
                                            <div class="col-md-9">    
                                                <div id="upload-message"></div>
                                            </div>
                                            <iframe id="upload_iframe" name="upload_iframe" src="#"></iframe>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div> 
                                        <div class="col-md-10">
                                                <ul id="image-container" class="list-group">
                                                    {$product_images}
                                                </ul>
                                                <button class="btn btn-primary btn-block update_image_order">Update Order</button>
                                                <div id="image_order_message"></div>
                                        </div>
                                        <div class="col-md-1"></div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
{/block} 