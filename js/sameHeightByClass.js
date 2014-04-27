//resize divs to same height via class identifyer
	function sameHeightByClass(divClass){
		//set all of chosen element heights to auto
		$('.'+divClass).height('auto');
		//max height is 0 (lowest)
		var maxHeight = 0;
		//for each div of corresponding class name
		$('.'+divClass).each(function(){
			//if height is greater than current maxHeight
			if($(this).height()>maxHeight){
				//Accept it as new maxHeight
				maxHeight = $(this).height();
			}
		});
		//Set all divs of corresponding class name to maxHeight
		$('.'+divClass).height(maxHeight);
	}
