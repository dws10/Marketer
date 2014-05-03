{block name="content"}
	
    <script type="text/javascript" src="../cont/js/edit_listing.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
            <form id="add_product">
            	<input type="hidden" name="listing_id" value="{$listing_id}"/>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">New Listing</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Product</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Select Product</label>
                                              <select class="form-control product_select" name="product">
                                              	<option value="0" selected>Select a product</option>
                                              	{$product_list}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="product-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Product Condition</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Select eBay Condition</label>
                                              <select class="form-control" name="condition_name" id="condition_options">
                                              	{$condition_opts}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="condition-error"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Condition Details</label>
                                              <textarea name="condition_details" class="form-control" rows="6" placeholder="Enter condition specific details">{$condition_desc}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                         <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Listing details</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Product Price</label>
                                              <input type="text" name="price" class="form-control" placeholder="Enter a price in GBP £" value="{$price}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Product Quantity</label>
                                              <input type="text" name="quantity" class="form-control" placeholder="Enter a quantity" value="{$quantity}"/>
                                            </div>
                                        </div>
                                        <div id="quantity-error"></div>
                                        <div id="price-error"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Listing Duration</label>
                                              <select class="form-control" name="duration" id="duration_options">
                                              	<option value="0" selected>Please select a duration</option>
                                                {$duration_opt}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="duration-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Shipping Method</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Select Shipping Template</label>
                                              <select class="form-control" name="shipping_template">
                                              	<option value="0" selected>Select a shipping template</option>
                                              	{$shipping_template_list}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="shipping-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Return Policy</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Select Return Template</label>
                                              <select class="form-control" name="return_template">
                                              	<option value="0" selected>Select a return template</option>
                                              	{$return_template_list}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="returns-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Display Template</h3>
                                    </div>
                                    <div class="panel-body">
                                    	<div class="form-group">
                                            <div class="input-group">
                                              <label class="input-group-addon">Select Display Template</label>
                                              <select class="form-control" name="display_template">
                                              	{$display_template_list}
                                              </select>
                                            </div>
                                        </div>
                                        <div id="display-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button class="btn btn-primary btn-block edit_listing">Edit Listing</button>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </form>   
        </div>
    </div>
{/block} 