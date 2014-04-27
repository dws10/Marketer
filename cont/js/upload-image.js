// JavaScript Document
$(document).ready(function(){
	$('#loading').hide();
	$('#upload_iframe').hide();

});
	function startUpload(){
		
		$('#loading').show();
		$('#upload_iframe').attr('src','../cont/php/upload_image.php');
		$('#fileupload').submit();
		return true;
	}
		
	function stopUpload(success, filename, file_id){ 
		var result = '';
		
		var msg = '' ;
		var template = '';
		
		if (success == 100){
			msg += '<div class="alert alert-success alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += filename+' The file was uploaded successfully!';
			msg += '</div>';
			
			
			template += '<li class="list-group-item">';
				template += '<form id="image_'+file_id+'">';
					template += '<div class="row">';
						template += '<input type="hidden" name="image_id[]" value="'+file_id+'" />';
						template += '<div class="col-md-1"></div>';
						template += '<div class="col-md-3">';
							template += '<a href="../images/products/'+filename+'" title="new_Image">';
								template += '<img src="../images/products/'+filename+'" alt="new_Image" class="img-responsive">';
							template += '</a>';
						template += '</div>';
						template += '<div class="col-md-5"></div>';
						template += '<div class="col-md-2">';
							template += '<button class="btn btn-danger remove_image">Delete</button>';
							template += '<img src="../images/loading.gif" height="30" width="30" class="delete_loading fade"/>';
						template += '</div>';
						template += '<div class="col-md-1"></div>';
					template += '</div>';
				template += '</form>';
			template += '</li>';
			
			$('#image-container').append($(template));
			
		}else if(success == 0){
			msg += '<div class="alert alert-danger alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += 'Empty file array, upload not found!';
			msg += '</div>';
		}else if(success == 1){
			msg += '<div class="alert alert-danger alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += filename+' using invalid file type!';
			msg += '</div>';
		}else if(success == 2){
			msg += '<div class="alert alert-danger alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += filename+' exceeds size limitation!';
			msg += '</div>';
		}else if(success == 3){
			msg += '<div class="alert alert-danger alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += 'Error - This item already has 4 images!';
			msg += '</div>';
		}else{
			msg += '<div class="alert alert-danger alert-dismissable">';
		  		msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				msg += 'There was an error uploading: '+filename+'!';
			msg += '</div>';
		}
		
		
		$('#upload-message').html(msg);
		$('#loading').hide();
		
		return true;
	}