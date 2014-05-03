{block name="content"}
	<script type="text/javascript" src="../cont/js/new_return_template.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Return Template</h3>
            	</div>
           		<div class="panel-body">
					<form id="new_return_template">
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Template Title</label>
                                <input type="text" class="form-control" name="template-title"/>
                            </div>
                        </div>
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Return Within</label>
                                <select class="form-control product_category" name="return-duration">
                                  <option value="Days_14">14 Days</option>
                                  <option value="Days_30">30 Days</option>
                                  <option value="Days_60">60 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Return Policy</label>
                                <textarea class="form-control" rows="15" name="return-policy"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<button class="btn btn-primary save_return_template">Add Template</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
    </div>
{/block} 