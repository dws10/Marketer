// JavaScript Document
$(document).ready(function(){
	$(".mark-dispatch-btn").click(function(){
		xajax_mark_as_dispatched(xajax.getFormValues(this.form));
		return false;
	});	
	$('#view-order').on('click', '.package-btn', function(){
		xajax_package_transaction(xajax.getFormValues(this.form));
		return false;
	});
	$('#view-order').on('click', '.unpackage-btn', function(){
		xajax_unpackage_transaction(xajax.getFormValues(this.form));
		return false;
	});
})