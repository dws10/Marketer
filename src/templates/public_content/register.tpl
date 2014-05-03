{block name="content"}
	<script type="text/javascript" src="./cont/js/client_register.js"></script>
    <script type="text/javascript" src="./libs/craftyclicks/crafty_postcode.class.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">Register for our services today!</h3>
            	</div>
           		<div class="panel-body">
                	<div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <form class="form-horizontal" id="register">
                            	<fieldset>
                            	<legend>Account Details</legend>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">First Name</label>
                                          <input type="text" class="form-control" placeholder="Enter First Name" name="fname" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="fname-error"></div> 
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Surname</label>
                                          <input type="text" class="form-control" placeholder="Enter Surname" name="sname" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="sname-error"></div> 
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Email Address</label>
                                          <input type="text" class="form-control" placeholder="Enter Email Address" name="email" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="email-error"></div> 
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Password</label>
                                          <input type="password" class="form-control" placeholder="Enter Password" name="password" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="password-error"></div> 
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Confirm Password</label>
                                          <input type="password" class="form-control" placeholder="Enter Password Confirmation" name="con_password" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="con-password-error"></div> 
                                </fieldset>
                                <fieldset>
                                <legend>Seller Store Details</legend>
                               
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Store Name</label>
                                          <input type="text" class="form-control" placeholder="Enter Store Name" name="store_name" autocomplete="off">
                                        </div>
                                    </div>
                        			<p>Please make use of our postcode search engine to easily locate and validate your business address. This location should 
                                    be where you dispatch the majority of your products from. If you cannot find your address or think it is wrong you can manually 
                                    enter your address below. During our initial launch we will only be catering to UK based online retailers.</p>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Postcode</label>
                                          <input type="text" class="form-control" placeholder="Enter Address Postcode" name="address_postcode" autocomplete="off">
                                          <span class="input-group-btn">
                                          	<a class="btn btn-default postcode-lookup btn" href="#">Search Address</a>
                                          </span>
                                          
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group address-select-holder" id="postcode-results">
										</div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Building Number/Name</label>
                                          <input type="text" class="form-control address-value" placeholder="Enter Building Number/Name" name="address_building" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Road</label>
                                          <input type="text" class="form-control address-value" placeholder="Enter Address Line One" name="address_road1" autocomplete="off">
                                          <input type="text" class="form-control address-value" placeholder="Enter Address Line Two" name="address_road2" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Town/City</label>
                                          <input type="text" class="form-control address-value" placeholder="Enter Address Town/City" name="address_town" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">County</label>
                                          <input type="text" class="form-control address-value" placeholder="Enter Address County" name="address_county" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Country</label>
                                          <select type="text" class="form-control" name="address_country">
                                          	<option value="1">United Kingdom</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div id="address-error"></div> 
                               	</fieldset>
                                <fieldset>
                                <legend>eBay Store Details</legend>
                                    <div id="store-name-error"></div> 
                                    <p>To use our websites eBay functionality you will need to register with a valid ebay authentication token.</p>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Ebay Authentication Token</label>
                                          <input type="text" class="form-control" placeholder="Enter Ebay Authentication Token" name="auth_token" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="auth-token-error"></div> 
                                </fieldset>
                                <fieldset>
                                	<legend>Payment Information</legend>
                                    <p>Many selling platforms choose to process payments with paypal to secure transactions. Please enter the email address for the paypal 
                                    account you wish to receive payments with when listed items are sold.</p>
                                    <div class="form-group">
                                        <div class="input-group">
                                          <label class="input-group-addon">Paypal Email Address</label>
                                          <input type="text" class="form-control" placeholder="Enter Paypal Email Address" name="paypal_email" autocomplete="off">
                                        </div>
                                    </div>
                                    <div id="paypal-email-error"></div> 
                                </fieldset>
                                <div class="form-group">
                                	<button class="btn btn-primary register-btn pull-right">Register</button>
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