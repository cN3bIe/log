/*v1:23.11.2016.0*/
style>
.popup_wrap, .popup{position:fixed;top:0;left:0;bottom:0;right:0;z-index:9999;cursor:pointer;}
.popup_wrap{background:rgba(0,0,0,.3);display:none;}
.popup{margin:auto;background: #fff;}
.close{position:absolute;top:-15px;right:-15px;width:30px;height:30px;border-radius:50%;overflow:hidden;}
script> 
$('.button_show_popup').click(function(){
	select_content = $(this).data('popup');
	content = $(select_content).clone();
	if(!$('.popup_wrap').length){
		$('body').append(
			$('<div class="popup_wrap">').append(
				$('<div class="popup">').append(
					$('<div class="popup_content">').append(content),
					$('<div class="close">').click(function(){
						$(this).parents('.popup_wrap').hide();
					})
				)
			)
		);
	} else $('.popup_content').replaceWith(content);
	$('.popup').css({'width':$(select_content).width(),'height':$(select_content).height()});
	$('.popup_wrap').show();
	$('.popup_wrap '+select_content).show();
	return false;
});
html>
button>
/*В кнопке указывается data-popup-атрибут в который запихивается селектор(для jQuery) элемента с контентом*/
<a href="/contacts/" class="order button_show_popup" data-popup="#feedback_form">заказать балкон</a>
content>
/*необходимо обернуть, если не обернут контент который будет появляться в popup-окошке*/
<div id="feedback_form" style="display:none">content</div>
