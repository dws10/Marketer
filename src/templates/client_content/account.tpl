{block name="content"}
	<script type="text/javascript" src="../cont/js/client_update.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">Update your account details</h3>
            	</div>
           		<div class="panel-body">
                	<div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <form id="client_update">
                            	<input type="hidden" name="user_id" autocomplete="off" value="{$user_id}">
                            	<h3><small>Account Details</small></h3>
                            	<div class="form-group">
                                    <div class="input-group">
                                      <label class="input-group-addon">First Name</label>
                                      <input type="text" class="form-control" placeholder="Enter First Name" name="fname" autocomplete="off" value="{$fname}">
                                    </div>
                                </div>
                                <div id="fname-error"></div> 
                            	<div class="form-group">
                                    <div class="input-group">
                                      <label class="input-group-addon">Surname</label>
                                      <input type="text" class="form-control" placeholder="Enter Surname" name="sname" autocomplete="off" value="{$sname}">
                                    </div>
                                </div>
                                <div id="sname-error"></div> 
                                <div class="form-group">
                                    <div class="input-group">
                                      <label class="input-group-addon">Email Address</label>
                                      <input type="text" class="form-control" placeholder="Enter Email Address" name="email" autocomplete="off" value="{$email}">
                                    </div>
                                </div>
                                <div id="email-error"></div> 
                                <div class="form-group">
                                    <div class="input-group">
                                      <label class="input-group-addon">New Password</label>
                                      <input type="password" class="form-control" placeholder="Enter Password" name="password" autocomplete="off">
                                    </div>
                                </div>
                                <div id="password-error"></div> 
                                <div class="form-group">
                                    <div class="input-group">
                                      <label class="input-group-addon">Confirm New Password</label>
                                      <input type="password" class="form-control" placeholder="Enter Password Confirmation" name="con_password" autocomplete="off">
                                    </div>
                                </div>
                                <div id="con-password-error"></div> 
                                <div class="form-group">
                                	<button class="btn btn-primary update-account-btn pull-right">Update</button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
            	</div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
{/block} 