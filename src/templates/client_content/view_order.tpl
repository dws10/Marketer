{block name="content"}
	<script type="text/javascript" src="../cont/js/view_order.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
        	<div class="panel panel-info">
            	<div class="panel-heading">
            		<h3 class="panel-title">Order Details #{$external_order_id} </h3>
            	</div>
           		<div class="panel-body" id="view-order">
					<div class="row">
                    	<div class="col-md-1"></div>
                        <div class="col-md-5"><h3>OrderID: </h3><strong>{$external_order_id}</strong></div>
                        <div class="col-md-5"><h3>Purchase Date: </h3><strong>{$purchase_date}</strong></div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row">
                    	<div class="col-md-1"></div>
                        <div class="col-md-5">
                        	<h3>Shipping Address</h3>
                        	<strong>{$shipping_address}</strong>
                        </div>
                        <div class="col-md-5">
                       		<h3>Order Total: </h3>
                        	<strong>{$order_total_price}</strong>
                            <h3>Status</h3>
                            <strong id="order-status">{$order_status}</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <h3>Product</h3>
                        </div>
                        <div class="col-md-1">
                            <h3>Price</h3>
                        </div>
                        <div class="col-md-3">
                            <h3>Condition</h3>
                        </div>
                        <div class="col-md-1">
                            <h3>Status</h3>
                        </div>
                        <div class="col-md-2">
                            <h3>Action</h3>
                        </div>
                    </div>
                    {$transaction_items}
                    <div class="row">
                    	<div class="col-md-4"></div>
                        <div class="col-md-4">
                        	<form>
                            	<input type="hidden" value="{$order_id}" name="order_id"/>
                        		{$dispatch_btn}
                        	</form>
                            <div id="dispatch-error"></div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                   
                </div>
            </div>   
        </div>
    </div>
{/block} 