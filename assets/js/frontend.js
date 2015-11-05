// JavaScript Document

$(document).ready(function(){



});

function validateForm(form){
	var result = true;
	form.find('input.error,textarea.error').removeClass('error');
	form.find('.required').each(function(){
		if($(this).is('[type=checkbox]')){
			if(!$(this).is(':checked')){
				alert($(this).attr('title'));
				result = false;
			}
		} else if($(this).val().length == 0){
			if(!$(this).hasClass('error'))
				$(this).addClass('error');
			result = false;
		} else if($(this).hasClass('email')){
			if(!$(this).val().isEmail()){
				if(!$(this).hasClass('error'))
					$(this).addClass('error');
				alert("Oppgi en gyldig e-postadresse");
				result = false;
			}
		} else if($(this).hasClass('pwdconfirm') && $('input[name="'+$(this).attr('data-related')+'"]').hasClass('required')){
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
