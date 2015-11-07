// JavaScript Document

$(document).ready(function(){

	$('#frm_registration').on('submit', function(e){
		e.preventDefault();
	});

	$('#frm_registration button#send_subscribe').on('click', function(e){
		e.preventDefault();
		if(validateForm($('#frm_registration'))){
			$.post('/api/register', { fullname: $('#frm_name').val(), email: $('#frm_email').val() }, function(data){
				if(data.success){
					$('#frm_name').val('');
					$('#frm_email').val('');
					$('#frm_registration').hide();
					$('.formcontainer .thanks').show();
				} else {
					alert(data.errordesc);
				}
				
			}, 'json');
		}
	});
	
	$('a.open_privacy').on('click', function(e){
		e.preventDefault();
		$('#lightbox').fadeIn();
	});
	$('#lightbox a.lbgbx_close').on('click', function(e){
		e.preventDefault();
		$('#lightbox').fadeOut();
	});

});

function validateForm(form){
	var result = true;
	form.find('.required').each(function(){
		if($(this).is('[type=checkbox]')){
			if(!$(this).is(':checked')){
				alert($(this).attr('title'));
				result = false;
			}
		} else if($(this).val().length == 0 && result){
			alert($(this).attr('title'));
			$(this).focus();
			result = false;
		} else if($(this).hasClass('email') && result){
			if(!$(this).val().isEmail()){
				alert("Enter a valid email address");
				$(this).focus();
				result = false;
			}
		} else if($(this).hasClass('pwdconfirm') && $('input[name="'+$(this).attr('data-related')+'"]').hasClass('required') && result){
			if($(this).val() != $('input[name="'+$(this).attr('data-related')+'"]').val()){
				alert("Passord stemmer ikke overens");
				$(this).focus();
				result = false;
			}
		}
	});
	return result;
}

String.prototype.isEmail = function(){
	return (this.valueOf().search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1);
}
