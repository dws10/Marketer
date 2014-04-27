{block name="content"}
	<script type="text/javascript" src="../cont/js/edit_return_template.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">New Return Template</h3>
            	</div>
           		<div class="panel-body">
					<form id="new_return_template">
                    	<input type="hidden" class="form-control" name="template-id" value="{$template_id}"/>
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Template Title</label>
                                <input type="text" class="form-control" name="template-title" value="{$template_title}"/>
                            </div>
                        </div>
                    	<div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Return Within</label>
                                <select class="form-control product_category" name="return-duration">
                                  {$template_duration}
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<label class="input-group-addon">Return Policy</label>
                                <textarea class="form-control" rows="15" name="return-policy">{$template_policy}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="input-group">
                            	<button class="btn btn-primary save_return_template">Update Template</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
    </div>
{/block} 