{block name="content"}
    <script type="text/javascript" src="../cont/js/end_listing.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">End Listing</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10" id="btn-holder">
                            <form>
                               	<input type="hidden" name="listing_id" value="{$listing_id}"/>
                               	<div class="form-group">
                                    <div class="input-group">
                                    	<label class="input-group-addon">Reason</label>
                                        <select name="reason" class="form-control">
                                            <option value="0">Please select a reason for delisting</option>
                                            <option value="Incorrect">Incorrect listing details</option>
                                            <option value="LostOrBroken">Item is lost or broken</option>
                                            <option value="NotAvailable">Item is not available</option>
                                            <option value="OtherListingError">Other listing error</option>
                                            <option value="SellToHighBidder">Sold to highest bidder</option>
                                        </select>
                                    </div>
                                </div>
                               	<div id="reason-error"></div>
                              	<button class="btn btn-primary btn-block end-listing">Confirm Delisting</button>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block} 