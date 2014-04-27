{block name="content"}
    <script src="../cont/js/add_product.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Product</h3>
            	</div>
           		<div class="panel-body">
                    <form id="add_product">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">General Attributes</h3>
                                    </div>
                                    <div class="panel-body">
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
                                              <input type="text" class="form-control" placeholder="Enter Product Title" name="title" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Product Description</label>
                                              <textarea class="form-control" name="description" rows="10"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div id="custom-attribute-row" class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <div class="panel panel-info top-articles">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Ebay Recommended</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul id="recommendation_btns" class="list-group">
                                           
                                        </ul>
                                        
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
                                           
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button class="btn btn-primary btn-block add_product">Add Product</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
    </div>
{/block} 