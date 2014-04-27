{block name="content"}
    <script type="text/javascript" src="../cont/js/list_listing.js"></script>
    <div class="row">
		<div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Preview Listing</h3>
                </div>
                <div class="panel-body">
                    <div class="row" id="preview">
                        <div class="col-md-12">
                           {$displayTemplateContent}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10" id="btn-holder">
                            <form>
                                <input type="hidden" name="listing_id" value="{$listing_id}"/>
                               <button class="btn btn-primary btn-block list-listing">Activate Listing</button>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block} 