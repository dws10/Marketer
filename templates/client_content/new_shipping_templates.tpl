{block name="content"}
	<script type="text/javascript" src="../cont/js/new_shipping_template.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Shipping Template</h3>
            	</div>
           		<div class="panel-body">
					<form id="new_shipping_template">
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Shipping Template Title</label>
                            	<input type="text" class="form-control" name="title" placeholder="Enter a template title"/>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Shipping Option</label>
                            	<select class="form-control" name="shipping_type">
                                	<option value="" selected>Please select a shipping option</option>
                                	{$shipping_options}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Dispatch Time (Days)</label>
                            	<input type="text" class="form-control" name="shipping_dispatch_time" placeholder="Enter dispatch time in days"/>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Shipping Cost (£)</label>
                            	<input type="text" class="form-control" name="shipping_cost" placeholder="Enter the shipping cost"/>
                            </div>
                        </div>
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Cost Per Extra Item (£)</label>
                            	<input type="text" class="form-control" name="shipping_additional_cost" placeholder="Enter the shipping cost per additional item"/>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<button class="btn btn-primary save_shipping_template">Save Template</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
    </div>
{/block} 