{block name="content"}
	
    <script src="../cont/js/category.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Category</h3>
            	</div>
           		<div class="panel-body">
                	<ul class="list-group">
                    	<li class="list-group-item">
                            <div class="btn-toolbar" role="toolbar">
                                <div class="col-md-3">
                                    <h3><small>Product Category Name</small></h3>
                                </div>
                                <div class="col-md-4">
                                    <h3><small>eBay Category</small></h3>
                                </div>
                                <div class="col-md-4">
                                    <h3><small>eBay Store Category</small></h3>
                                </div>
                                <div class="col-md-1">
                                    <h3><small>Add</small></h3>
                                </div>
                            </div>
    					</li>
                        <li class="list-group-item">
                            <div class="btn-toolbar" role="toolbar">
                            	<form id="test_id">
                                	<div class="col-md-3">
                                        <div class="form-group">
                                        	<input type="text" class="form-control" name="cat_name" id="cat_name"/>
                                        </div>
                                	</div>
                                    <div class="col-md-4">
                                        <div class="form-group ebay_category_select_holder">
                                        	<input type="hidden" name="ebay_cat"/>
                                        	<select class="form-control ebay_category_select">
                                            	<option value='' disabled selected>Select A Primary eBay Category</option>
                                            	{$top_level_category_options}
                                            </select>
                                        </div>
                                	</div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        	<select class="form-control ebay_category_select" name="ebay_store_cat">
                                            	<option value='' disabled selected>Select An eBay Store Category</option>
                                            	{$store_categories}
                                            </select>
                                        </div>
                                	</div>
                                	<div class="col-md-1">
                                        <div class="btn-group btn-group-sm pull-right">
                                            <button type="button" class="btn btn-default btn-block add_cat">Add New</button>
                                        </div>
                                    </div>
                            	</form>
                            </div>
    					</li>
                    </ul>
                </div>
            </div>   
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">Existing Categories</h3>
            	</div>
           		<div class="panel-body">
                	<ul class="list-group">
                    	<li class="list-group-item">
                            <div class="btn-toolbar" role="toolbar">
                                <div class="col-md-3">
                                    <h3><small>Product Category Name</small></h3>
                                </div>
                                <div class="col-md-4">
                                    <h3><small>eBay Category</small></h3>
                                </div>
                                <div class="col-md-4">
                                    <h3><small>eBay Store Category</small></h3>
                                </div>
                                <div class="col-md-1">
                                    <h3><small>Edit</small></h3>
                                </div>
                            </div>
    					</li> 
                        {$existing_cats}
                    </ul>              
            	</div>
            </div>   
        </div>
    </div>
{/block} 