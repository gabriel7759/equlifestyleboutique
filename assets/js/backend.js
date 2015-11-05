$(document).ready(function(){
	
	$('#addon_addoption_content,#addon_addoption_picture,#addon_addoption_video').on('click', function(e){
		e.preventDefault();
		var type = 0;
		if($(this).attr('id')=='addon_addoption_picture')
			type = 1;
		else if($(this).attr('id')=='addon_addoption_video')
			type = 2;
		var newidx = parseInt($('#num_options').val())+1;
		$('#num_options').val(newidx);
		
		var newopt = '<li id="newck_'+newidx+'"><input type="hidden" name="position_'+newidx+'" value="'+newidx+'" class="setposition">'
						+ '<input type="hidden" name="type_'+newidx+'" value="'+type+'" />'
						+ '<a href="#" class="delopt">remove</a>'
						+ '<a href="#" class="sortopt">sort</a>';
				if(type==0){
					newopt += '<div class="title">'
						+ '<label>Subtítulo</label><br />'
						+ '<input type="text" name="subtitle_'+newidx+'" value="" class="required" title="Ingrese el subtitulo" autocomplete="off" />'
						+ '</div>'
						+ '<div class="description" id="cont_description_'+newidx+'">'
						+ '<label>Contenido</label><br />'
						+ '<textarea name="content_'+newidx+'" class="ckeditor"></textarea>'
						+ '</div>';
				} else if(type == 1){
					newopt += '<div class="picture">'
						+ '<label>Picture 1 <span class="req">*</span> <span>(JPG 289 x 165)</span></label>'
						+ '<div class="file">'
						+ '<div class="fileinput">'
						+ '<input type="text" name="picture1_'+newidx+'_tmp" value="" disabled="disabled">'
						+ '<div><input type="file" name="picturef1_'+newidx+'" /></div>'
						+ '</div>'
						+ '<div class="fname">'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank"><img src="../assets/files/experiences/pictures/" height="65" alt="" /></a>'
						+ '<a href="#" class="del">Delete</a>'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank" class="iname"></a>'
						+ '<input type="hidden" name="picture1_'+newidx+'" value="" />'
						+ '<input type="checkbox" name="picture1_'+newidx+'_del" value="1" />'
						+ '</div>'
						+ '</div>'
						+ '</div><br />'
						+ '<div class="picture">'
						+ '<label>Picture 2 <span class="req">*</span> <span>(JPG 289 x 165)</span></label>'
						+ '<div class="file">'
						+ '<div class="fileinput">'
						+ '<input type="text" name="picture2_'+newidx+'_tmp" value="" disabled="disabled">'
						+ '<div><input type="file" name="picturef2_'+newidx+'" /></div>'
						+ '</div>'
						+ '<div class="fname">'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank"><img src="../assets/files/experiences/pictures/" height="65" alt="" /></a>'
						+ '<a href="#" class="del">Delete</a>'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank" class="iname"></a>'
						+ '<input type="hidden" name="picture2_'+newidx+'" value="" />'
						+ '<input type="checkbox" name="picture2_'+newidx+'_del" value="1" />'
						+ '</div>'
						+ '</div>'
						+ '</div><br />'
						+ '<div class="picture">'
						+ '<label>Picture 3 <span class="req">*</span> <span>(JPG 289 x 165)</span></label>'
						+ '<div class="file">'
						+ '<div class="fileinput">'
						+ '<input type="text" name="picture3_'+newidx+'_tmp" value="" disabled="disabled">'
						+ '<div><input type="file" name="picturef3_'+newidx+'" /></div>'
						+ '</div>'
						+ '<div class="fname">'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank"><img src="../assets/files/experiences/pictures/" height="65" alt="" /></a>'
						+ '<a href="#" class="del">Delete</a>'
						+ '<a href="../assets/files/experiences/pictures/" target="_blank" class="iname"></a>'
						+ '<input type="hidden" name="picture3_'+newidx+'" value="" />'
						+ '<input type="checkbox" name="picture3_'+newidx+'_del" value="1" />'
						+ '</div>'
						+ '</div>'
						+ '</div>';
				} else if(type == 2){
					newopt += '<div class="title">'
						+ '<label>Video</label><br />'
						+ '<input type="text" name="video_'+newidx+'" value="" class="required" title="Ingrese el url del video" autocomplete="off" />'
						+ '</div>';
				}
		$('#optionslist').append(newopt);
		
		if(type == 0){
			$('li#newck_'+newidx+' textarea.ckeditor').tinymce({
				// Location of TinyMCE script
				script_url : '/assets/tinymce/tinymce.min.js',
				selector: "textarea",
				theme: "modern",
				height: 300,
				plugins: [ "advlist autolink link image lists charmap print preview hr anchor pagebreak",
							"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
							"table contextmenu directionality emoticons paste textcolor responsivefilemanager" ],
				toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview code | fontsizeselect",
				image_advtab: true ,
				external_filemanager_path:"/assets/filemanager/",
				filemanager_title:"Responsive Filemanager",
				external_plugins: { "filemanager" : "/assets/filemanager/plugin.min.js"}
			});

		}
		
	});
	
	$('#optionslist').on('change', '.checkcustomdesc', function(){
		var myname = $(this).attr('name').split('_')[1];
		if(parseInt($('input[name="'+$(this).attr('name')+'"]:checked').val())==1){
			$('div#cont_description_'+myname).slideDown();
		} else {
			$('div#cont_description_'+myname).slideUp();
		}
	});

	$('#optionslist').sortable({
		handle: '.sortopt',
		stop: function(){
			i=1;
			$('#optionslist input.setposition').each(function(){
				$(this).val(i);
				i++;
			});
		}
	});

	$('input[name="inctype"]').on('change', function(){
		if($('input[name="inctype"]:checked').val() == 1){
			var idx = 1;
			$('#optionslist > li').each(function(){
				if(idx == 1){
					$(this).find('a.delopt,a.sortopt').remove();
					$(this).find('input.setposition').val(1);
				} else {
					$(this).remove();
				}
				idx++;
			});
			$('#addoptioncont').hide();
			$('input#num_options').val(1);
		} else {
			$('#optionslist > li div.price').append('<a href="#" class="delopt">remove</a> <a href="#" class="sortopt">sort</a>');
			$('#addoptioncont').show();
		}
	});

	$('input[name="showincard"]').on('change', function(){
		if($('input[name="showincard"]:checked').val() == 1){
			$('#optionslist > li div.showincard').show();
		} else {
			$('#optionslist > li div.showincard').hide();
		}
	});
	
	$('#optionslist').on('click', 'a.delopt', function(e){
		e.preventDefault();
		if(confirm("Are you sure you want to remove the selected option?")){
			$(this).parent().remove();
		}
	});
	
	/* DEFAULT */
	
	/* identity options */
	$('#header .identity').on('mouseenter', function(){
		$('#header .identity ul').show();
	}).on('mouseleave', function(){
		$('#header .identity ul').hide();
	});
	
	/* menu options */
	$('#menu > li').on('mouseenter', function(){
		$(this).find('ul').show();
	}).on('mouseleave', function(){
		$(this).find('ul').hide();
	});
	
	/* dropdown*/
	$('div.dropdown').on('mouseenter', function(){
		$(this).addClass('hover').find('ul').show();
	}).on('mouseleave', function(){
		$(this).removeClass('hover').find('ul').hide();
	});
	
	/* message */
	$('div.message a.close').on('click', function(e){
		e.preventDefault();
		$(this).parent().fadeOut();
	});
	
	/* tooltips */
	$('*[data-tooltip]').on('mouseover', function(){
		var elem = $(this);
		var tip  = $('#tooltip');
		var text = elem.data('tooltip');
			tip.text(text);
		var left = parseInt(elem.offset().left-tip.width()/2-2)+'px';
		var top  = parseInt(elem.offset().top+elem.height()+9)+'px';
			tip.css({'left': left, 'top': top}).show(0);
	}).on('mouseout', function(){
		$('#tooltip').hide(0);
	});
	
	/* delete */
	$('td a.delete').on('click', function(e){
		e.preventDefault();
		var itemname = $(this).parent().parent().find('td[data-itemname]').data('itemname');
		var url = $(this).attr('href').split('?id=');
		$('#modal-confirm .box-body p span').html('You are about to delete <strong>'+itemname+'</strong>');
		$('#modal-overlay').fadeIn(300, function(){
			$('#modal-confirm').data('action', url[0]).data('itemid', url[1]).show(0);
		});
	});
	
	/* delete */
	$('td a.delete').on('click', function(e){
		e.preventDefault();
		var itemname = $(this).parent().parent().find('td[data-itemname]').data('itemname');
		var url = $(this).attr('href').split('?id=');
		$('#modal-confirm .box-body p span').html('You are about to delete <strong>'+itemname+'</strong>');
		$('#modal-overlay').fadeIn(300, function(){
			$('#modal-confirm').data('action', url[0]).data('itemid', url[1]).show(0);
		});
	});
	

	/* bulk action */
	$('div.box-table button.bulk').on('click', function(){
		if ($(this).hasClass('disabled')) {
			return false;
		}
		if ( ! $('form[name="list"] select[name="command"]').val().length) {
			alert('Seleccione una acción');
			return;
		}
		var command = $('div.box-table div.bulk select option:selected').text().toLowerCase();
		var value   = $('div.box-table div.bulk select option:selected').val();
		var count   = $('div.box-table input.select:checked').length;
		var url     = self.location.href.split('?');
//		var action  = (value=='delete') ? $(this).data('action').replace('/status', '/delete') : $(this).data('action');
		if(command=='activate' || command=='activar' || command=='deactivate' || command=='desactivar' || command=='publish' || command=='publicar')
			var action  = url[0].replace('/index', '/status');
		else
			var action  = url[0].replace('/index', '/'+value);
		var status  = (command=='activate' || command=='activar' || command=='publish' || command=='publicar') ? 1 : 0;
		$('#modal-confirm .box-body p span').html('You are about to <strong>'+command+'</strong> '+count+' items.');
		$('#modal-overlay').fadeIn(300, function(){
			$('#modal-confirm').data('action', action).data('status', status).show(0);
		});
	});
	
	/* modal */
	$('#modal-confirm a.close, #modal-confirm button.cancel').on('click', function(e){
		e.preventDefault();
		$('#modal-confirm').data('action', '').data('itemid', '').hide(0);
		$('#modal-overlay').fadeOut();
	});
	$('#modal-confirm button.accept').on('click', function(){
		if ( ! $(this).hasClass('disabled')) {
			var action = $('#modal-confirm').data('action');
			var id     = $('#modal-confirm').data('itemid');
			var status = $('#modal-confirm').data('status');
			var token  = $('form[name="list"]').data('token');
			if (id.length) {
				$('form[name="list"] input.select').attr('checked', false);
			}
			$(this).addClass('disabled');
			$('form[name="list"] input[name="id"]').val(id);
			$('form[name="list"] input[name="status"]').val(status);
			$('form[name="list"] input[name="csrf_token"]').val(token);
			$('form[name="list"]').attr('action', action).attr('method', 'post').submit();
//			$('form[name="list"]').attr('action', action).attr('method', 'post');
		}
	});
	
	/* blank */
	$('a.blank').on('click', function(e){
		e.preventDefault();
	});
	
	/* filters */
	$('.box-search').each(function(){
		var obj = $(this);
			obj.height(obj.parent().height());
	});
	
	$('input[name="select-all"]').on('click', function(){
		var table = $(this).parent().parent().parent().parent();
		var box   = table.parent();
		if ($(this).is(':checked')) {
			table.find('input.select').attr('checked', true);
			table.find('tr:gt(0)').addClass('selected');
			box.find('button.bulk').removeClass('disabled');
		} else {
			table.find('input.select').attr('checked', false);
			table.find('tr:gt(0)').removeClass('selected');
			box.find('button.bulk').addClass('disabled');
		}
	});
	$('input.select').on('click', function(){
		var row   = $(this).parent().parent();
		var table = row.parent().parent();
		var box   = table.parent();
		var total = table.find('input.select').length;
		($(this).is(':checked')) ? row.addClass('selected') : row.removeClass('selected');
		var checked = table.find('input.select:checked').length;
		(checked==0) ? box.find('button.bulk').addClass('disabled') : box.find('button.bulk').removeClass('disabled');
		(checked==total) ? table.find('input[name="select-all"]').attr('checked', true) : table.find('input[name="select-all"]').attr('checked', false);
	});
	
	// disabled button
	$('button.disabled, a.disabled').on('click', function(e){
		e.preventDefault();
	});
	
	// action buttons
	$('#content button.add').on('click', function(){
		var url = self.location.href.split('/');
			url.pop();
			url.push('form');
		self.location.href = url.join('/');
	});
	$('form.form button.cancel').on('click', function(){
		var url = self.location.href.split('/');
			url.pop();
			url.push('index');
		self.location.href = url.join('/');
	});
	
	// form validation
	$('form.validate').on('submit', function(){
		var success  = true;
		var form     = $(this);
		// prevent submitting the form twice
		if (form.find('button.disabled').length>0)
			return false;
		// remove error
		form.off('keydown').on('keydown, change', 'input, select', function(){ // ensure that the same handler isn't binded twice
			$(this).removeClass('error');
		});
		// validate non empty elements
		form.find('input.required, select.required, textarea.required').each(function(){
			if ($.trim($(this).val()) == '') {
				alert($(this).attr('title'));
				$(this).focus().addClass('error');
				success = false;
				return false;
			}
		});
		// form didn't pass validations
		if (!success) {
			return false;
		} else {
			
			if($('input#tamiz_confirmed').length){
				if(parseInt($('input#tamiz_confirmed').val()) == 1){
					return true;
				} else {
					$('div#saveconfirmcont').fadeIn();
					return false;
				}
			} else {
				form.find('button[type="submit"]').addClass('disabled');
				return true;
			}
		}
	});
	
	// date picker
	$("input.date").datepicker({ dateFormat: "yy-mm-dd", firstDay: 0, changeMonth: true, changeYear: true, yearRange: '1940:2015' });
	
	// sticky menu
	$(document).scroll(function(){
		($('html').offset().top <= -43) ? $('#menu').addClass('sticky') : $('#menu').removeClass('sticky');
	});
	
	// filters
	$('div.box-header p.filters a').on('click', function(e){
		e.preventDefault();
		$('form[name="list"] input[name="status"]').val($(this).data('status'));
		$('form[name="list"] input[name="page"]').val(1);
		$('form[name="list"]').submit();
	});
	
	// pagination
	$('div.box-table div.pagination a').on('click', function(e){
		e.preventDefault();
		if ( ! $(this).hasClass('disabled')) {
			$('form[name="list"] input[name="page"]').val($(this).data('page'));
			$('form[name="list"]').submit();
		}
	});
	
	// sort
	if ($('.sortable').length) {
		$('.sortable').sortable({
			handle: '.handle'
		});
	}
	
	// export
	$('div.box-table button.export').on('click', function(){
		self.location.href = $(this).data('action');
	});
	
	// custom select control
	$('div.select p').on('click', function(){
		var list = $(this).parent().find('ul');
		if (list.is(':visible')) { 
			list.hide();
			$('#select-overlay').remove();
		} else {
			list.show();
		}
		$('body').append('<div id="select-overlay"></div>');
	});
	$('div.select ul span.option').on('click', function(){
		var value = $(this).data('value');
		var id = $(this).data('id');
		$('#'+id).hide();
		$('#'+id+' span.option').removeClass('selected');
		$(this).addClass('selected');
		$('#'+id).parent().find('p').text($(this).text());
		$('#'+id).parent().find('input[type="hidden"]').val(value);
		$('#select-overlay').remove();
	});
	$('#select-overlay').on('click', function(){ 
		$(this).remove();
		$('div.select > ul').hide();
	});
	
	// quizzes
	if ($('input[name="type"]').length) {
		var quizz = $('input[name="type"]:checked').val();
		$('.quizztype input[type="text"]').removeClass('required');
		$('.quizztype').hide();
		$('#quizztype'+quizz+' input[type="text"]').addClass('required');
		$('#quizztype'+quizz).show();
	}
	$('input[name="type"]').on('click', function(){
		var quizz = $(this).val();
		$('.quizztype input[type="text"]').removeClass('required');
		$('.quizztype').hide();
		$('#quizztype'+quizz+' input[type="text"]').addClass('required');
		$('#quizztype'+quizz).show();
	});
	
	$('form.form').on('change', 'input[type="file"]', function(e){
		$(this).parent().parent().find('input[type="text"]').val($(this).val());
		var fname = $(this).parent().parent().parent().find('.fname');
		fname.show();
		fname.find('input[type="checkbox"]').removeAttr('checked');
		
		var files = e.target.files;
		for (var i = 0, f; f = files[i]; i++) {
			
			if (!f.type.match('image.*')) {
				continue;
			}
				var reader = new FileReader();
				reader.onload = (function(theFile) {
					return function(e) {
						fname.find('img').attr('src', e.target.result);
						fname.find('a.iname').text(theFile.name);
					};
				})(f);
				reader.readAsDataURL(f);
/*
			if (!f.type.match('image.*')) {
				var reader = new FileReader();
				reader.onload = (function(theFile) {
					return function(e) {
						fname.find('img').attr('src', e.target.result);
						fname.find('a.iname').text(theFile.name);
					};
				})(f);
				reader.readAsDataURL(f);
			} else {
				var reader = new FileReader();
				reader.onload = (function(theFile) {
					return function(e) {
						fname.find('a.iname').text(theFile.name);
					};
				})(f);
				reader.readAsDataURL(f);
			}
*/
		}
	});
	
	$('.fname a.del').on('click', function(e){
		e.preventDefault();
		$(this).parent().hide();
		$(this).parent().parent().find('input[type="text"]').val($(this).val());
		$(this).parent().find('input[type="checkbox"]').attr('checked', 'checked');
	});
	
	/*
	$('textarea.ckeditor_small').each(function(i){
		var textarea = $(this);
		var id = textarea.attr('id');
		CKEDITOR.replace(id, {
			width: 450,
			height: 150
		});
		
	});
	*/
	
	
	if ($('.nested-sortable').length) {
		
		var maxLevel = 3;
		if($('.nested-sortable').is("[data-maxLevel]"))
			maxLevel = $('.nested-sortable').attr('data-maxLevel');
		
		$('.nested-sortable').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			maxLevels: maxLevel,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		});
		$('div.box-table button.serialize').click(function(){
			var serialized = $('.nested-sortable').nestedSortable('serialize');
			var action = $('div.box-table button.sort').data('action');
			var token  = $('form[name="list"]').data('token');
			$('form[name="list"] input[name="serialized"]').val(serialized);
			$('form[name="list"] input[name="csrf_token"]').val(token);
			$('form[name="list"]').attr('action', action).attr('method', 'post').submit();
		})
		
		$('div.box-table button.sort').on('click', function(){
			$(this).addClass('disabled');
			$('div.box-table div.sortable-head, div.box-table .nested-sortable').fadeIn(300);
		});
		$('div.sortable-head button.cancel').on('click', function(){
			$('div.box-table button.sort').removeClass('disabled');
			$('div.box-table div.sortable-head, div.box-table .nested-sortable').fadeOut(300);
		});
	}

	if ($("#map").length) {
		var Zoom = 12;
		var lat = $("form[name='save'] input[name='latitude']").val();
		var lng = $("form[name='save'] input[name='longitude']").val();
		var centerCoord = new google.maps.LatLng(19.415998291127025, -99.13268127194897);
		if (lat!="" && lng!="") {
			centerCoord = new google.maps.LatLng(lat, lng);
			Zoom = 17;
		}
		var mapOptions = {
			zoom: Zoom,
			center: centerCoord,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map"), mapOptions);
		
		google.maps.event.addListener(map, 'center_changed', function() {
			var location = map.getCenter();
			$("form[name='save'] input[name='latitude']").val(location.lat());
			$("form[name='save'] input[name='longitude']").val(location.lng());
			$('label#labelcoords span').text(location.lat()+"-"+location.lng());
		});
		
	}
	
//	if ($("div.richtext textarea").length) {
		$("textarea.ckeditor, textarea.ckeditor_small").tinymce({
			// Location of TinyMCE script
			script_url : '/assets/tinymce/tinymce.min.js',
			selector: "textarea",
			theme: "modern",
			height: 300,
			plugins: [ "advlist autolink link image lists charmap print preview hr anchor pagebreak",
						"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
						"table contextmenu directionality emoticons paste textcolor responsivefilemanager" ],
			toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
			toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview code | fontsizeselect",
			image_advtab: true ,
			external_filemanager_path:"/assets/filemanager/",
			filemanager_title:"Responsive Filemanager",
			external_plugins: { "filemanager" : "/assets/filemanager/plugin.min.js"}
// 			width: 800,
//			content_css : '/assets/styles/ckeditor.css'
		});
//	}



});