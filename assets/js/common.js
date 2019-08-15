$(document).ajaxStart(function() {$('.ajax-load').fadeIn()});
$(document).ajaxComplete(function(event, xhr, settings) {$('.ajax-load').fadeOut()});


//custom slide bar init
$(".sidebar").mCustomScrollbar({
	axis:"y",
	theme: "minimal-dark",
	scrollInertia: 100
});
$(".content").mCustomScrollbar({
	axis:"y",
	theme: "minimal-dark",
	scrollInertia: 100
});

//sidebar toggle
$(".content .overlay").click(function(e) {$('.toggle-btn').click()});
$('.toggle-btn').click(function(e) {
    $('.main').toggleClass('small');
	$('.topbar').children('.logo').toggleClass('small');
});

//sum menu toggle
$('.has-sub>a').click(function(e) {
	$this=$(this).parent();
	$parent=$this.parent();
	
	if($this.hasClass('show')){
		var animateHeight = $this.children('ul').height();

		if($parent.hasClass('menu-item')==false){
			$this.children('ul').animate({
				height: "0px"
			},
			  200,function(){
				$this.toggleClass('show');
				$(this).height('auto');
				$parent.height('auto');
			});
		}
		else{
			$this.children('ul').animate({
				height: "0px"
			  },
			  200,function(){
			  	$this.toggleClass('show');
			  	$(this).height('auto');	
			  });
		}
	}
	else{
		$parent.find('.has-sub').each(function(index, element) {
            if($(this).hasClass('show') ){
				$(this).children('ul').animate({
					height: "0px"
				  },
				  200,function(){$(this).parent().toggleClass('show');$(this).height('auto');$(this).parent().parent().height('auto')});
			}
        });
		
		$this.addClass('show');
		var animateHeight = $this.children('ul').height();
		$this.children('ul').height(0);
		
		if (animateHeight != 0) {
		$this.children('ul').animate({
			height: animateHeight + "px"
		  },
		  200,function(){$(this).height('auto');$(this).height('auto');});
		}
	}  
});

//auto alert dismisable
$('.alert').each(function(index, element) {
    $(this).addClass('alert-dismissible');
	$(this).prepend('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
});
//file input
$('[data-file-input]').click(function(e) {
    $(this).parent().parent().children('[name^="' + $(this).data('file-input') + '"]').click();
});
//show input file name
function updateName(file){
	$(file).parent().children('[type="text"]').val($(file).val().split(/(\\|\/)/g).pop());
}